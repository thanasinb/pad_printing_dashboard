<?php

ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require '../update/establish.php';
require '../const-status.php';

$sql = "SELECT 
            machine_queue.id_task, 
            machine_queue.id_staff,
            planning.id_job,
            planning.item_no, 
            planning.operation, 
            planning.op_color, 
            planning.op_side, 
            planning.date_due, 
            planning.qty_per_pulse2 AS qty_per_tray, 
            planning.qty_order, 
            planning.qty_comp, 
            planning.qty_open, 
            planning.run_time_std,
            planning.datetime_update AS last_update, 
            activity.no_pulse2 + activity.no_pulse3 AS bf_qty_shif,
            activity_rework.no_pulse2 + activity_rework.no_pulse3 AS rw_qty_shif, 
            activity_downtime.no_pulse2 + activity_downtime.no_pulse3 AS dt_qty_shif 
        FROM machine_queue 
        LEFT JOIN planning ON machine_queue.id_task=planning.id_task 
        LEFT JOIN activity ON machine_queue.id_task=activity.id_task AND activity.status_work<" . STATUS_CLOSED . " 
        LEFT JOIN activity_rework ON machine_queue.id_task=activity_rework.id_task AND activity_rework.status_work<" . STATUS_CLOSED . " 
        LEFT JOIN activity_downtime ON machine_queue.id_task=activity_downtime.id_task AND activity_downtime.status_downtime<" . STATUS_CLOSED . " 
        WHERE machine_queue.queue_number=1 AND machine_queue.id_machine='" . $_GET['id_mc'] . "'";

$data = $conn->query($sql)->fetch_assoc();

if ($data['bf_qty_shif']!=null){
    $data['qty_shif']=intval($data['bf_qty_shif']);
}elseif ($data['rw_qty_shif']!=null){
    $data['qty_shif']=intval($data['rw_qty_shif']);
}elseif ($data['dt_qty_shif']!=null){
    $data['qty_shif']=intval($data['dt_qty_shif']);
}

//$data = array_merge($data, array("sql"=>$sql));
//echo json_encode(preg_replace("!\r?\n!", "", $data));

$data = array_merge($data);
echo json_encode($data);

require '../update/terminate.php';
