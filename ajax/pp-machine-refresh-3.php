<?php

ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require '../update/establish.php';
require '../const-status.php';

$sql = "SELECT 
            machine.id_mc, 
            machine_queue.id_task, 
            machine_queue.id_staff,
            planning.item_no, 
            planning.operation, 
            planning.op_color, 
            planning.op_side, 
            planning.date_due, 
            planning.qty_per_pulse2 AS qty_per_tray, 
            planning.qty_order, 
            planning.qty_comp, 
            planning.qty_open, 
            planning.run_time_std 
        FROM machine 
        LEFT JOIN machine_queue ON machine.id_mc=machine_queue.id_machine
        LEFT JOIN planning ON machine_queue.id_task=planning.id_task 
        WHERE machine_queue.queue_number=1 OR machine_queue.queue_number IS NULL ORDER BY id_mc";

$array_machine_queue = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

$rework='n';
$array_dashboard=array();
date_default_timezone_set('Asia/Bangkok');
$date = new DateTime();
$date_in_sec = $date->getTimestamp();

foreach ($array_machine_queue as $mq){
    // IF THE MACHINE HAS A TASK ASSIGNED
    if ($mq['id_task']!=null){
        $mq['run_time_std'] = number_format((floatval($mq['run_time_std'])*3600), 2);
        $sql = "SELECT SUM(no_pulse2) AS qty_process, SUM(no_pulse3) AS qty_manual FROM activity
                WHERE status_work<" . STATUS_UPDATED . "
                AND id_task=" . $mq['id_task'];
        $data_activity_sum = $conn->query($sql)->fetch_assoc();
        $data_activity_sum['qty_process'] = intval($data_activity_sum['qty_process']);
        $data_activity_sum['qty_manual'] = intval($data_activity_sum['qty_manual']);
        $mq['qty_comp'] = intval($mq['qty_comp']);
        $mq['qty_open'] = intval($mq['qty_open']);
        $mq['qty_order'] = intval($mq['qty_order']);
        $mq['qty_accum']=$data_activity_sum['qty_process']+$data_activity_sum['qty_manual']+$mq['qty_comp'];
        $mq['percent']=round(($mq['qty_accum']/$mq['qty_order'])*100,0);
        if ($mq['qty_accum']>$mq['qty_order']){
            $mq['est_sec']=-$mq['percent'];
            $mq['run_time_open'] = "00:00:00";
        }else{
            $mq['est_sec']=($mq['qty_order']-$mq['qty_accum'])*$mq['run_time_std'];
            $mq['run_time_open'] = gmdate("H:i:s", $mq['est_sec']);
        }
        if ($mq['est_sec']>86400){
            $days = floor($mq['est_sec']/86400);
            $mq['run_time_open'] = $days . ":" . $mq['run_time_open'];
        }
        $mq['est_time'] = date('d-m-y H:i:s', $date_in_sec + $mq['est_sec']);
        $mq['date_due'] = date("d-m-y", strtotime($mq['date_due']));

        // IF THE MACHINE IS OCCUPIED
        if($mq['id_staff']!=null){
            $sql = "SELECT id_staff, status_work, total_work, run_time_actual, (no_pulse2 + no_pulse3) AS qty_shif FROM activity 
            WHERE status_work<" . STATUS_CLOSED . " AND id_task=" . $mq['id_task'] . " AND id_machine='" . $mq["id_mc"] . "'";
            $data_activity_time = $conn->query($sql)->fetch_assoc();
            if ($data_activity_time['status_work']==null)
            {
                $sql = "SELECT id_staff, status_work, total_work, run_time_actual, (no_pulse2 + no_pulse3) AS qty_shif FROM activity_rework 
                WHERE status_work<" . STATUS_CLOSED . " AND id_task=" . $mq['id_task'] . " AND id_machine='" . $mq["id_mc"] . "'";
                $data_rework_time = $conn->query($sql)->fetch_assoc();

                // NOT BACKFLUSH
                if($data_rework_time['status_work']==null){
                    // DOWNTIME
                    $sql = "SELECT activity_downtime.id_staff, activity_downtime.status_downtime, code_downtime.code_downtime, (no_pulse2 + no_pulse3) AS qty_shif FROM activity_downtime 
                    INNER JOIN code_downtime ON activity_downtime.id_downtime=code_downtime.id_downtime 
                    WHERE status_downtime<" . STATUS_CLOSED . " AND id_task=" . $mq['id_task'] . " AND id_machine='" . $mq["id_mc"] . "'";
                    $data_activity_downtime = $conn->query($sql)->fetch_assoc();
                    if (strcmp($data_activity_downtime['status_downtime'], '2') != 0){
                        $data_activity_time['status_work'] = -1;
                    }else{
                        $data_activity_time['status_work'] = 2;
                    }
                    $data_activity_time['id_staff'] = $data_activity_downtime['id_staff'];
                    $data_activity_time['code_downtime'] = $data_activity_downtime['code_downtime'];
                    $data_activity_time['qty_shif'] = intval($data_activity_downtime['qty_shif']);
                }else{
                    // REWORK
                    $data_activity_time=$data_rework_time;
                    $rework='y';
                }
            }
            if ($data_activity_time['run_time_actual']==null) {
                $data_activity_time['run_time_actual'] = '0.00';
            }

            $sql = "SELECT date_eff, shif, SUM(no_pulse2)+SUM(no_pulse3) AS qty_shif FROM activity WHERE id_task=" . $mq['id_task'] . " AND id_machine='" . $mq["id_mc"] . "' AND id_staff='" . $mq['id_staff'] . "' GROUP BY date_eff, shif UNION 
                    SELECT date_eff, shif, SUM(no_pulse2)+SUM(no_pulse3) AS qty_shif FROM activity_rework WHERE id_task=" . $mq['id_task'] . " AND id_machine='" . $mq["id_mc"] . "' AND id_staff='" . $mq['id_staff'] . "' GROUP BY date_eff, shif UNION
                    SELECT date_eff, shif, SUM(no_pulse2)+SUM(no_pulse3) AS qty_shif FROM activity_downtime WHERE id_task=" . $mq['id_task'] . " AND id_machine='" . $mq["id_mc"] . "' AND id_staff='" . $mq['id_staff'] . "' GROUP BY date_eff, shif
                    ORDER BY date_eff DESC";
            $data_qty_shif = $conn->query($sql)->fetch_assoc();

            $array_dashboard[] = array_merge($mq, $data_activity_sum, $data_activity_time, $data_qty_shif, array('rework'=>$rework));
        }
        // ELSE THE MACHINE IS NOT OCCUPIED
        else{
            $sql = "SELECT SUM(no_pulse2) AS qty_process, SUM(no_pulse3) AS qty_manual FROM activity
                WHERE status_work<" . STATUS_UPDATED . "
                AND id_task=" . $mq['id_task'];
            $data_activity_sum = $conn->query($sql)->fetch_assoc();
            $data_activity_sum['qty_process'] = intval($data_activity_sum['qty_process']);
            $data_activity_sum['qty_manual'] = intval($data_activity_sum['qty_manual']);
            $data_activity_sum['qty_shif'] = 0;
            $mq['qty_comp'] = intval($mq['qty_comp']);
            $mq['qty_open'] = intval($mq['qty_open']);
            $mq['qty_order'] = intval($mq['qty_order']);
            $mq['qty_accum']=$data_activity_sum['qty_process']+$data_activity_sum['qty_manual']+$mq['qty_comp'];
            $mq['percent']=round(($mq['qty_accum']/$mq['qty_order'])*100,0);
            $array_dashboard[] = array_merge($mq, $data_activity_sum, array('run_time_actual'=>0.00, 'est_sec'=>-1, 'status_work'=>0, 'qty_shif'=>0, 'rework'=>$rework));
        }
    }
    // ELSE THE MACHINE DOES NOT HAVE A TASK ASSIGNED
    else{
        $array_dashboard[] = array_merge($mq, array('percent'=>-2, 'est_sec'=>-2, 'status_work'=>0, 'qty_shif'=>0, 'rework'=>$rework));
    }
}

echo json_encode($array_dashboard);

require '../update/terminate.php';
