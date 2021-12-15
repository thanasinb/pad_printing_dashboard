<?php
require '../update/establish.php';

$sql = "SELECT id_activity, id_task, time_start, CURRENT_TIMESTAMP() AS time_current, total_food, total_toilet, no_send FROM activity WHERE status_work<3 AND id_machine = '" . $_GET["id_mc"] . "'";
//echo $sql;

$result = $conn->query($sql);
$data_activity = $result->fetch_assoc();

if(empty($data_activity)) {

    $sql = "SELECT *, CURRENT_TIMESTAMP() AS time_current FROM activity_downtime WHERE status_downtime=1 AND id_machine = '" . $_GET["id_mc"] . "' ";
    $query_activity_downtime = $conn->query($sql);
    $data_activity_downtime = $query_activity_downtime->fetch_assoc();

    if(!empty($data_activity_downtime)) {
        $time_start = strtotime($data_activity_downtime["time_start"]);
        $time_current = strtotime($data_activity_downtime["time_current"]);
        $time_total =  gmdate('H:i:s', $time_current-$time_start);

        $sql = "UPDATE activity_downtime SET ";
        $sql = $sql . "status_downtime=3,";
        $sql = $sql . "total_work='" . $time_total . "',";
        $sql = $sql . "time_close='" . $data_activity_downtime["time_current"] . "' ";
        $sql = $sql . "WHERE id_activity_downtime=" . $data_activity_downtime["id_activity_downtime"];
        $query_activity_downtime = $conn->query($sql);

        $data_json = json_encode(array("time_work"=>$time_total), JSON_UNESCAPED_UNICODE);
        print_r($data_json);
    }
}
else {
    $total_food = strtotime("1970-01-01 " . $data_activity["total_food"] . " UTC");
    $total_toilet = strtotime("1970-01-01 " . $data_activity["total_toilet"] . " UTC");
    $total_break = $total_food + $total_toilet;
    $time_start = strtotime($data_activity["time_start"]);
    $time_current = strtotime($data_activity["time_current"]);
    $time_total =  gmdate('H:i:s', $time_current-$time_start-$total_break);

    $sql = "UPDATE activity SET status_work=3, total_work='" . $time_total . "', time_close='" . $data_activity["time_current"] . "' WHERE id_activity=" . $data_activity["id_activity"];
    $result = $conn->query($sql);

//    $data_json = json_encode(array("time_work"=>$time_total, "sql"=>$sql), JSON_UNESCAPED_UNICODE);
    $data_json = json_encode(array("time_work"=>$time_total), JSON_UNESCAPED_UNICODE);
    print_r($data_json);
}

require '../update/terminate.php';

