<?php
require 'establish.php';

$sql = "SELECT *, CURRENT_TIMESTAMP() AS time_current FROM activity_downtime WHERE status_downtime=1 ";
$sql = $sql . "AND id_machine = '" . $_GET["id_mc"] . "' ";
$sql = $sql . "AND id_staff = ";
$sql = $sql . "(SELECT id_staff FROM staff WHERE id_rfid='" . $_GET['id_rfid'] . "')";

$query_activity_downtime = $conn->query($sql);
$data_activity_downtime = $query_activity_downtime->fetch_assoc();

if(empty($data_activity_downtime)) {
    $data_json = json_encode(array("code"=>"016"), JSON_UNESCAPED_UNICODE);
    print_r($data_json);
} else {
    $time_start = strtotime($data_activity_downtime["time_start"]);
    $time_current = strtotime($data_activity_downtime["time_current"]);
    $time_total =  gmdate('H:i:s', $time_current-$time_start);

    $sql = "UPDATE activity_downtime SET ";
    $sql = $sql . "status_downtime=3,";
    $sql = $sql . "total_work='" . $time_total . "',";
    $sql = $sql . "time_close='" . $data_activity_downtime["time_current"] . "' ";
    $sql = $sql . "WHERE id_activity_downtime=" . $data_activity_downtime["id_activity_downtime"];
    $query_activity_downtime = $conn->query($sql);
//    echo $sql;

    $sql = "UPDATE machine_queue SET id_staff='' WHERE id_machine='" . $_GET["id_mc"] . "' AND queue_number=1";
    $result = $conn->query($sql);

    $data_json = json_encode(array("time_work"=>$time_total, "time_start"=>$time_start, "time_current"=>$time_current), JSON_UNESCAPED_UNICODE);
    print_r($data_json);
}
require "contact.php";
require 'terminate.php';

