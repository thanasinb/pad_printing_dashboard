<?php

ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);

require '../update/establish.php';

$sql_where = "(status_work=3 OR status_work=5) AND (time_start BETWEEN '" . $_GET['shif_start'] . "' AND '" . $_GET['shif_end'] . "')";

//$sql = "SELECT id_activity, DATE(time_start) as date_start, activity.id_staff, id_shif FROM activity INNER JOIN staff ON activity.id_staff=staff.id_staff WHERE " . $sql_where . " AND id_job IN (SELECT DISTINCT id_job FROM activity WHERE " . $sql_where . ")";
//$sql = "SELECT DISTINCT id_task, id_job, operation, qty_complete FROM activity INNER JOIN planning ON activity.id_task=planning.id_task WHERE (status_work=3 OR status_work=5) AND (time_start BETWEEN '2021-10-09 07:00:00' AND '2021-10-09 15:00:00')";
//$sql = "SELECT id_activity, id_task, id_job, operation, DATE(time_start) as date_start, activity.id_staff, id_shif, total_work, no_pulse1 as qty_process FROM activity INNER JOIN staff ON activity.id_staff=staff.id_staff WHERE (status_work=3 OR status_work=5) AND (time_start BETWEEN '2021-10-09 07:00:00' AND '2021-10-09 15:00:00') AND id_job IN (SELECT DISTINCT id_job FROM activity WHERE (status_work=3 OR status_work=5) AND (time_start BETWEEN '2021-10-09 07:00:00' AND '2021-10-09 15:00:00'))";

// SELECT ALL id_task THAT ARE CLOSED (status_work=3) OR EXPORTED BUT NOT YET RE-IMPORTED (status_work=5)
$sql = "SELECT DISTINCT id_task FROM activity WHERE (status_work=3 OR status_work=5) AND (time_start BETWEEN '2021-10-09 07:00:00' AND '2021-10-09 15:00:00') ORDER BY id_task";
$query_activity_task = $conn->query($sql);
$array_export = array();
while($data_activity_task = $query_activity_task->fetch_assoc()){
    // SELECT TASK INFO
    $sql = "SELECT * FROM planning WHERE id_task=" . $data_activity_task['id_task'];
    $query_planning_all = $conn->query($sql);
    $data_planning_all = $query_planning_all->fetch_assoc();

//    $sql = "SELECT DISTINCT id_machine FROM activity WHERE id_task=15 AND (status_work=3 OR status_work=5) AND (time_start BETWEEN '2021-10-09 07:00:00' AND '2021-10-09 15:00:00') ORDER BY id_machine";
//    $query_activity_machine = $conn->query($sql);

//    $data_activity_machine = $query_activity_machine->fetch_assoc();
//    array_push($array_export[$data_activity_task['id_task']], $data_activity_task['id_task']);
//    $array_task = array();
//    while ($data_activity_machine = $query_activity_machine->fetch_assoc()){
//        $sql = "SELECT * FROM activity INNER JOIN staff ON activity.id_staff=staff.id_staff WHERE id_machine='" . $data_activity_machine['id_machine'] . "' AND id_task=" .  $data_activity_task['id_task'] . " AND (status_work=3 OR status_work=5) AND (time_start BETWEEN '2021-10-09 07:00:00' AND '2021-10-09 15:00:00') ORDER BY time_start";
//        $query_activity_all = $conn->query($sql);
//        $array_machine = array();
//        while ($data_activity_all = $query_activity_all->fetch_assoc()){
//            if (strcmp($data_activity_all['id_shif'], "A") == 0) {
//                $data_activity_all['id_shif'] = $_GET['shif_letter'] . '4';
//            } elseif (strcmp($data_activity_all['id_shif'], "B") == 0) {
//                $data_activity_all['id_shif'] = $_GET['shif_letter'] . '6';
//            } elseif (strcmp($data_activity_all['id_shif'], "C") == 0) {
//                $data_activity_all['id_shif'] = $_GET['shif_letter'] . '5';
//            }
////            array_push($array_machine, array($data_activity_machine['id_machine'] => $data_activity_all));
//            array_push($array_machine[$data_activity_machine['id_machine']],$data_activity_all);
//        }
//        array_push($array_task, $array_machine);
//    }
//    array_push($data_planning_all, $array_task);
//    array_push($array_export, $data_planning_all);

    // SELECT ACTIVITY INFOS ASSOCIATED TO THIS id_task ORDER BY time_start
    $sql = "SELECT * FROM activity INNER JOIN staff ON activity.id_staff=staff.id_staff WHERE id_task=" .  $data_activity_task['id_task'] . " AND (status_work=3 OR status_work=5) AND (time_start BETWEEN '2021-10-09 07:00:00' AND '2021-10-09 15:00:00') ORDER BY time_start";
    $query_activity_all = $conn->query($sql);
    $array_activity = array();
    while($data_activity_all = $query_activity_all->fetch_assoc()) {
        if (strcmp($data_activity_all['id_shif'], "A") == 0) {
            $data_activity_all['id_shif'] = $_GET['shif_letter'] . '4';
        } elseif (strcmp($data_activity_all['id_shif'], "B") == 0) {
            $data_activity_all['id_shif'] = $_GET['shif_letter'] . '6';
        } elseif (strcmp($data_activity_all['id_shif'], "C") == 0) {
            $data_activity_all['id_shif'] = $_GET['shif_letter'] . '5';
        }
        array_push($array_activity, $data_activity_all);
    }
    array_push($data_planning_all, $array_activity);
    array_push($array_export, $data_planning_all);
}

echo json_encode($array_export, JSON_UNESCAPED_UNICODE);
//echo json_encode(array("sql"=>$sql), JSON_UNESCAPED_UNICODE);

require '../update/terminate.php';
