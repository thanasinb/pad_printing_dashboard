<?php
require 'establish.php';
require 'lib_get_staff_by_rfid.php';
require 'lib_get_info_from_activity_type.php';
require 'lib_get_active_activity_by_activity_id_type_staff_and_machine.php';
require 'lib_update_count.php';

$data_staff_rfid = get_staff_by_rfid($conn, $_GET['id_rfid']);
if(empty($data_staff_rfid)){
    $data_json = json_encode(array('code'=>'011', 'message'=>'Invalid RFID'), JSON_UNESCAPED_UNICODE);
    print_r($data_json);
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
        print_r($data_json);
    } else {
        $status_work = 3;
        $data_json = update_count($conn, $table, $status_work,
            $_GET['id_activity'],
            $_GET['no_send'],
            $_GET['no_pulse1'],
            $_GET['no_pulse2'],
            $_GET['no_pulse3'],
            $_GET['multiplier']);

        if(empty($_GET['code_downtime'])) {
            $sql = "UPDATE machine_queue SET id_staff='' WHERE id_machine='" . $_GET["id_mc"] . "' AND queue_number=1";
            $result = $conn->query($sql);
            print_r($data_json);
        }
    }
}

require "contact.php";
require 'terminate.php';

