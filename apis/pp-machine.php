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

    if(intval($r['id_activity'])!=0){
        $sql = "SELECT * FROM " . $table . "
            WHERE " . $str_activity . "=" . $r['id_activity'];
        $query_activity = $conn->query($sql);
        $r = array_merge($r, mysqli_fetch_assoc($query_activity));
    }

    $id_task = intval($r['id_task']);
    $sql = "SELECT SUM(no_pulse2) AS qty_process, SUM(no_pulse3) AS qty_manual 
        FROM activity WHERE status_work<6 AND id_task=" . $id_task;
    $query_activity_sum = $conn->query($sql);
    $data_activity_sum = $query_activity_sum->fetch_assoc();

    if ($data_activity_sum['qty_process']==null){
        $data_activity_sum['qty_process']='0';
    }
    if ($data_activity_sum['qty_manual']==null){
        $data_activity_sum['qty_manual']='0';
    }

    $data_machine_queue[] = array_merge($r, $data_activity_sum);
}
print json_encode($data_machine_queue);

require '../update/terminate.php';
