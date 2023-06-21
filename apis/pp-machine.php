<?php

ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);

require '../update/establish.php';
require '../update/lib_get_info_from_activity_type.php';

$sql = "SELECT * FROM machine_queue 
    INNER JOIN planning ON machine_queue.id_task = planning.id_task 
    WHERE queue_number=1 ORDER BY id_machine ASC";
$query_machine_queue = $conn->query($sql);
$data_machine_queue = array();
while($r = mysqli_fetch_assoc($query_machine_queue)) {

    list($table, $str_activity, $str_status) = get_info_from_activity_type($r['activity_type']);

    // IF THIS MACHINE IS NOT IDLE, GET THE ACTIVITY INFO
    if(intval($r['id_activity'])!=0){
        $sql = "SELECT * FROM " . $table . "
            WHERE " . $str_activity . "=" . $r['id_activity'];
        $query_activity = $conn->query($sql);
        $r = array_merge($r, mysqli_fetch_assoc($query_activity));
    }

    $id_task = intval($r['id_task']);
    $sql = "SELECT SUM(no_pulse2) AS qty_process, 
        SUM(no_pulse3) AS qty_manual 
        FROM activity WHERE status_work<6 AND id_task=" . $id_task;
    $query_activity_sum = $conn->query($sql);
    $data_activity_sum = $query_activity_sum->fetch_assoc();

    if ($data_activity_sum['qty_process']==null){
        $data_activity_sum['qty_process']='0';
    }
    if ($data_activity_sum['qty_manual']==null){
        $data_activity_sum['qty_manual']='0';
    }

    $data_activity_sum['qty_accum'] =
        intval($data_activity_sum['qty_process']) +
        intval($data_activity_sum['qty_manual']) +
        intval($r['qty_comp']);

    $data_activity_sum['percent'] = round(
        ($data_activity_sum['qty_accum']/$r['qty_order'])*100);

    $data_activity_sum['run_time_open'] =
        (intval($r['qty_order']) - $data_activity_sum['qty_accum']) *
        floatval($r['run_time_std']) * 3600;

    $seconds_of_a_day = 86400;
    $run_time_open_temp = $data_activity_sum['run_time_open'];
    $run_time_day = floor($run_time_open_temp/$seconds_of_a_day);
    $run_time_open_temp %= $seconds_of_a_day;
    $run_time_hr = floor($run_time_open_temp/3600);
    $run_time_open_temp %= 3600;
    $run_time_min = floor($run_time_open_temp/60);
    $run_time_sec = $run_time_open_temp % 60;

    $str_run_time_day = strval($run_time_day);

    if ($run_time_hr < 10){
        $str_run_time_hr = "0" . strval($run_time_hr);
    }else{
        $str_run_time_hr = strval($run_time_hr);
    }

    if($run_time_min < 10){
        $str_run_time_min = "0" . strval($run_time_min);
    }else{
        $str_run_time_min = strval($run_time_min);
    }

    if($run_time_sec < 10){
        $str_run_time_sec = "0" . strval($run_time_sec);
    }else{
        $str_run_time_sec = strval($run_time_sec);
    }
//    $str_run_time_hr = strval($run_time_hr);
//    $str_run_time_min = strval($run_time_min);
//    $str_run_time_sec = strval($run_time_sec);

    $data_activity_sum['run_time_open'] =
        $str_run_time_day . " days " .
        $str_run_time_hr . ":" .
        $str_run_time_min . ":" .
        $str_run_time_sec;

    unset($data_activity_sum['qty_manual']);

    $data_machine_queue[] = array_merge($r, $data_activity_sum);
}
print json_encode($data_machine_queue);

require '../update/terminate.php';
