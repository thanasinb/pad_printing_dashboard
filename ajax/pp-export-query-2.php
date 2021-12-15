<?php

ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);

require '../update/establish.php';

$sql_where = "(status_work=3 OR status_work=5) AND (time_start BETWEEN '" . $_GET['shif_start'] . "' AND '" . $_GET['shif_end'] . "')";

//$sql = "SELECT planning.* FROM activity INNER JOIN planning ON activity.id_task=planning.id_task WHERE (status_work=3 OR status_work=5) AND (time_start BETWEEN '2021-10-09 07:00:00' AND '2021-10-09 15:00:00') GROUP BY activity.id_task ORDER BY activity.id_job, activity.operation";
$sql = "SELECT planning.* FROM activity INNER JOIN planning ON activity.id_task=planning.id_task WHERE " . $sql_where . " GROUP BY activity.id_task ORDER BY activity.id_job, activity.operation";
//echo $sql . "<br><br>";
//$sql_json = $sql;

$query_planning = $conn->query($sql);
$array_export = array();

while ($data_planning = $query_planning->fetch_assoc()){
//    array_push($array_export, $data_planning);

//    $sql = "SELECT id_machine FROM activity WHERE id_task=" . $data_planning['id_task'] . " AND (status_work=3 OR status_work=5) AND (time_start BETWEEN '2021-10-09 07:00:00' AND '2021-10-09 15:00:00') GROUP BY id_machine ORDER BY id_machine";
    $sql = "SELECT id_machine FROM activity WHERE id_task=" . $data_planning['id_task'] . " AND " . $sql_where . " GROUP BY id_machine ORDER BY id_machine";
//    echo $sql . "<br>";
    $query_activity_machine = $conn->query($sql);
    $array_task = array();
    while ($data_activity_machine = $query_activity_machine->fetch_assoc()){
//        $sql = "SELECT * FROM activity INNER JOIN staff ON activity.id_staff=staff.id_staff WHERE id_machine='" . $data_activity_machine['id_machine'] . "' AND id_task=" . $data_planning['id_task'] . " AND (status_work=3 OR status_work=5) AND (time_start BETWEEN '2021-10-09 07:00:00' AND '2021-10-09 15:00:00') ORDER BY time_start";
        $sql = "SELECT * FROM activity INNER JOIN staff ON activity.id_staff=staff.id_staff WHERE id_machine='" . $data_activity_machine['id_machine'] . "' AND id_task=" . $data_planning['id_task'] . " AND " . $sql_where . " ORDER BY time_start";
        //        echo $sql . "<br>";
        $query_activity_all = $conn->query($sql);
        $array_machine=array();
        while ($data_activity_all = $query_activity_all->fetch_assoc()){
            if (strcmp($data_activity_all['id_shif'], "A") == 0) {
                $data_activity_all['id_shif'] = $_GET['shif_letter'] . '4';
            } elseif (strcmp($data_activity_all['id_shif'], "B") == 0) {
                $data_activity_all['id_shif'] = $_GET['shif_letter'] . '6';
            } elseif (strcmp($data_activity_all['id_shif'], "C") == 0) {
                $data_activity_all['id_shif'] = $_GET['shif_letter'] . '5';
            }
            array_push($array_machine, $data_activity_all);
        }
        array_push($array_task, $array_machine);
    }
    array_push($data_planning, $array_task);
    array_push($array_export, $data_planning);
//    echo "<br>";
}

echo json_encode($array_export, JSON_UNESCAPED_UNICODE);
//echo json_encode(array("sql", $sql_json), JSON_UNESCAPED_UNICODE);

require '../update/terminate.php';
