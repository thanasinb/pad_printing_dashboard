<?php
require 'establish.php';
require 'lib_get_staff_by_rfid.php';
require 'lib_get_info_from_activity_type.php';
require 'lib_get_break_info_from_activity_type.php';
require 'lib_get_active_activity_by_activity_id_type_staff_and_machine.php';
require 'lib_add_break.php';

$data_staff_rfid = get_staff_by_rfid($conn, $_GET['id_rfid']);
if(empty($data_staff_rfid)){
    $data_json = json_encode(array('code'=>'013', 'message'=>'Invalid RFID'), JSON_UNESCAPED_UNICODE);
}
else{
    list($table, $str_activity, $str_status) = get_info_from_activity_type($_GET['activity_type']);
    $data_activity = get_active_activity_by_activity_id_type_staff_and_machine(
        $conn,
        $table,
        $str_activity,
        $str_status,
        $_GET['id_activity'],
        $data_staff_rfid['id_staff'],
        $_GET['id_mc']);
    if(empty($data_activity)){
        $data_json = json_encode(array('code'=>'014', 'message'=>'Activity mismatches'), JSON_UNESCAPED_UNICODE);
    }
    else{
        list($table, $table_break, $str_activity, $str_status) = get_break_info_from_activity_type(intval($_GET['activity_type']));
        $data_break = add_break($conn, $table, $table_break, $str_activity, $str_status, $_GET['id_activity'], $data_staff_rfid['id_staff'], $_GET['id_mc'], $_GET["break_code"]);
        $data_json = json_encode($data_break, JSON_UNESCAPED_UNICODE);
    }
}

print_r($data_json);

require "contact.php";
require 'terminate.php';

