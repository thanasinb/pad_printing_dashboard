<?php
require 'establish.php';
require 'lib_get_staff_by_rfid.php';
require 'lib_get_info_from_activity_type.php';
require 'lib_get_active_activity_by_activity_id_type_staff_and_machine.php';

$data_staff_rfid = get_staff_by_rfid($conn, $_GET['id_rfid']);
if(empty($data_staff_rfid)){
    $data_json = json_encode(array('code'=>'011', 'message'=>'Invalid RFID'), JSON_UNESCAPED_UNICODE);
}
else {
    list($table, $str_activity, $str_status) = get_info_from_activity_type($_GET['activity_type']);
    $data_activity = get_active_activity_by_activity_id_type_staff_and_machine(
        $conn,
        $table,
        $str_activity,
        $str_status,
        $_GET['id_activity'],
        $data_staff_rfid['id_staff'],
        $_GET['id_mc']);
    if(empty($data_activity)) {
        $data_json = json_encode(array("code"=>"012", 'message'=>'Activity mismatches'), JSON_UNESCAPED_UNICODE);
    } else {
        $total_food = strtotime("1970-01-01 " . $data_activity["total_food"] . " UTC");
        $total_toilet = strtotime("1970-01-01 " . $data_activity["total_toilet"] . " UTC");
        $total_break = $total_food + $total_toilet;
        $time_start = strtotime($data_activity["time_start"]);
        $time_current = strtotime($data_activity["time_current"]);
        $time_total =  gmdate('H:i:s', $time_current-$time_start-$total_break);

        $sql = "UPDATE " . $table . " SET ";
        $sql = $sql . "status_work=3,";
        $sql = $sql . "total_work='" . $time_total . "',";
        $sql = $sql . "time_close='" . $data_activity["time_current"] . "',";
        $sql = $sql . "no_send=" . $_GET["no_send"] . ",";
        $sql = $sql . "no_pulse1=" . $_GET["no_pulse1"] . ",";
        $sql = $sql . "no_pulse2=" . $_GET["no_pulse2"] . ",";
        $sql = $sql . "no_pulse3=" . $_GET["no_pulse3"];
        $sql = $sql . " WHERE id_activity=" . $data_activity["id_activity"];
        $result = $conn->query($sql);

        $data_json = json_encode(array("time_work"=>$time_total), JSON_UNESCAPED_UNICODE);

        $sql = "UPDATE machine_queue SET id_staff='' WHERE id_machine='" . $_GET["id_mc"] . "' AND queue_number=1";
        $result = $conn->query($sql);
    }
}

print_r($data_json);

require "contact.php";
require 'terminate.php';

