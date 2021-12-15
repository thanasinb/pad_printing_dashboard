<?php
require 'establish.php';

$sql = "SELECT id_activity, time_break, total_break FROM activity WHERE status_work=2 AND id_machine='" . $_GET["id_mc"] . "' ";
//$sql = $sql . "AND id_staff=(SELECT id_staff FROM staff WHERE id_rfid='" . $_GET["id_rfid"] . "')";
//echo $sql;
$result = $conn->query($sql);
$data_activity = $result->fetch_assoc();

$sql = "SELECT CURRENT_TIME";
$result = $conn->query($sql);
$data_time_current = $result->fetch_assoc();

if(empty($data_activity)) {
    $data_json = json_encode(array("code"=>"010"), JSON_UNESCAPED_UNICODE);
    print_r($data_json);
}else{
    $total_break = strtotime("1970-01-01 " . $data_activity["total_break"] . " UTC");
    $time_break = strtotime($data_activity["time_break"]);
    $time_current = strtotime($data_time_current["CURRENT_TIME"]);
    $diff = $time_current-$time_break;
    $total_break_text =  gmdate('H:i:s', $time_current - $time_break + $total_break);

//    $total_break = date_create($data_activity["total_break"]);
//    $time_break = date_create($data_activity["time_break"]);
//    $time_current = date_create($data_time_current["CURRENT_TIME"]);

//    $diff = date_diff($time_break, $time_current);
//    $total_break->add($diff);
//    $total_break_text = $total_break->format("H:i:s");

    $sql = "UPDATE activity SET status_work=1, total_break='" . $total_break_text . "' WHERE id_activity=" . $data_activity["id_activity"];
    $result = $conn->query($sql);

    $sql = "UPDATE machine SET ";
    $sql = $sql . "time_contact=CURRENT_TIMESTAMP()";
    $sql = $sql . " WHERE id_mc='" . $_GET["id_mc"] . "'";
    $result = $conn->query($sql);

    $data_json = json_encode(array("total_break"=>$total_break_text), JSON_UNESCAPED_UNICODE);
    print_r($data_json);
}
require 'terminate.php';


