<?php

ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require '../update/establish.php';
require '../const-status.php';

$sql = "SELECT id_task FROM machine_queue WHERE queue_number=1 AND id_machine='" . $_GET["id_mc"] . "'";
$query_machine_queue = $conn->query($sql);
$data_machine_queue = $query_machine_queue->fetch_assoc();
$id_task = intval($data_machine_queue['id_task']);
//echo $sql . "<br>";

// ACCUMULATE THE PROCESSED QTY WHICH HAS NOT BEEN RE-IMPORTED
$sql = "SELECT SUM(no_pulse2) AS qty_process, SUM(no_pulse3) AS qty_manual FROM activity
            WHERE status_work<" . STATUS_UPDATED . " 
            AND id_task=" . $id_task;
$data_activity_sum = $conn->query($sql)->fetch_assoc();

$sql = "SELECT SUM(no_pulse2) AS qty_process, SUM(no_pulse3) AS qty_manual FROM activity_downtime
            WHERE status_downtime<" . STATUS_UPDATED . " 
            AND id_task=" . $id_task;
$data_downtime_sum = $conn->query($sql)->fetch_assoc();

$data_activity_sum['qty_process'] = intval($data_activity_sum['qty_process'])+intval($data_downtime_sum['qty_process']);
$data_activity_sum['qty_manual'] = intval($data_activity_sum['qty_manual'])+intval($data_downtime_sum['qty_manual']);

//echo $sql . "<br>";
if ($data_activity_sum['qty_process']==null){
    $data_activity_sum['qty_process']='0';
}
if ($data_activity_sum['qty_manual']==null){
    $data_activity_sum['qty_manual']='0';
}

// SELECT THE ACTIVE BACKFLUSH ACTIVITY
$sql = "SELECT id_staff, status_work, total_work, no_pulse1, run_time_actual FROM activity 
        WHERE status_work<" . STATUS_CLOSED . " AND id_task=" . $id_task . " AND id_machine='" . $_GET["id_mc"] . "'";
$query_activity_time = $conn->query($sql);
$data_activity_time = $query_activity_time->fetch_assoc();

// SELECT THE ACTIVE REWORK ACTIVITY
$sql = "SELECT id_staff, status_work, total_work, no_pulse1, run_time_actual FROM activity_rework 
        WHERE status_work<" . STATUS_CLOSED . " AND id_task=" . $id_task . " AND id_machine='" . $_GET["id_mc"] . "'";
$query_rework_time = $conn->query($sql);
$data_rework_time = $query_rework_time->fetch_assoc();

// SELECT THE DOWNTIME ACTIVITY OF SUCH TASK
$sql = "SELECT activity_downtime.id_staff, activity_downtime.status_downtime, code_downtime.code_downtime FROM activity_downtime 
    INNER JOIN code_downtime ON activity_downtime.id_downtime=code_downtime.id_downtime 
    WHERE status_downtime<" . STATUS_CLOSED . " AND id_task=" . $id_task . " AND id_machine='" . $_GET["id_mc"] . "'";
$query_activity_downtime = $conn->query($sql);
$data_activity_downtime = $query_activity_downtime->fetch_assoc();

$active_work = 0;
if(!empty($data_activity_time)){
    $active_work++;
}
if(!empty($data_activity_downtime)){
    $active_work++;
}
if(!empty($data_rework_time)){
    $active_work++;
}

$rework='n';
//if (!empty($data_activity_time) && !empty($data_activity_downtime)){
if ($active_work>1){
    echo json_encode(array("code" => "020", "message" => "The activity exists in both activity and activity_downtime tables"), JSON_UNESCAPED_UNICODE);
} else {
    if ($data_activity_downtime['status_downtime']!=null) {
        if (strcmp($data_activity_downtime['status_downtime'], '2') != 0){
            $data_activity_time['status_work'] = -1;
        }else{
            $data_activity_time['status_work'] = 2;
        }
        $data_activity_time['id_staff'] = $data_activity_downtime['id_staff'];
        $data_activity_time['code_downtime'] = $data_activity_downtime['code_downtime'];
    }elseif ($data_rework_time['status_work']!=null){
        $data_activity_time=$data_rework_time;
        $rework='y';
    }
    if ($data_activity_time['status_work']==null) {
        $data_activity_time['status_work'] = '0';
    }
    if ($data_activity_time['run_time_actual']==null) {
        $data_activity_time['run_time_actual'] = '0';
    }
    $sql = "SELECT task_complete, status_backup, qty_order, qty_comp AS qty_complete, qty_open, run_time_std, qty_per_pulse2 AS qty_per_tray, divider.divider as divider FROM planning INNER JOIN divider ON planning.op_color=divider.op_color AND planning.op_side=divider.op_side WHERE id_task=" . $id_task;
    $query_planning = $conn->query($sql);
    $data_planning = $query_planning->fetch_assoc();
    $data_planning['run_time_std'] = number_format((floatval($data_planning['run_time_std'])*3600), 2); // UNIT = SECONDS

    echo json_encode(array_merge($data_activity_sum, $data_planning, $data_activity_time, array('rework'=>$rework)));
}

require '../update/terminate.php';
