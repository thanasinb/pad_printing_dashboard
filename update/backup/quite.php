<?php
require 'establish.php';

$sql = "SELECT id_activity, time_start, total_break, no_send FROM activity WHERE status_work=1 AND id_machine = '" . $_GET["id_mc"] . "'";
$result = $conn->query($sql);
$data_activity = $result->fetch_assoc();

$sql = "SELECT CURRENT_TIME";
$result = $conn->query($sql);
$data_time_current = $result->fetch_assoc();

if(empty($data_activity)) {
    $data_json = json_encode(array("code"=>"011"), JSON_UNESCAPED_UNICODE);
    print_r($data_json);
} else {
    if (intval($data_activity["no_send"]) < $_GET["no_send"]) {
        $total_break = strtotime("1970-01-01 " . $data_activity["total_break"] . " UTC");
        $time_start = strtotime($data_activity["time_start"]);
        $time_current = strtotime($data_time_current["CURRENT_TIME"]);
        $time_total =  gmdate('H:i:s', $time_current-$time_start-$total_break);

        $sql = "UPDATE activity SET ";
        $sql = $sql . "status_work=3,";
        $sql = $sql . "total_work='" . $time_total . "',";
        $sql = $sql . "time_close='" . $data_time_current["CURRENT_TIME"] . "',";
        $sql = $sql . "no_send=" . $_GET["no_send"] . ",";
        $sql = $sql . "no_pulse1=" . $_GET["no_pulse1"] . ",";
        $sql = $sql . "no_pulse2=" . $_GET["no_pulse2"] . ",";
        $sql = $sql . "no_pulse3=" . $_GET["no_pulse3"];
        $sql = $sql . " WHERE id_activity=" . $data_activity["id_activity"];
        $result = $conn->query($sql);

        $sql = "UPDATE machine SET ";
        $sql = $sql . "time_contact='" . $data_time_current["CURRENT_TIME"] . "'";
        $sql = $sql . " WHERE id_mc='" . $_GET["id_mc"] . "'";
        $result = $conn->query($sql);

        $data_json = json_encode(array("time_work"=>$time_total), JSON_UNESCAPED_UNICODE);
        print_r($data_json);
    }else{
        $data_json = json_encode(array("code"=>"012"), JSON_UNESCAPED_UNICODE);
        print_r($data_json);
    }
}

require 'terminate.php';

