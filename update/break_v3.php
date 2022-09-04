<?php
require 'establish.php';
require 'lib_get_staff_by_rfid.php';
require 'lib_get_active_activity_by_activity_id_type_staff_and_machine.php';

$data_staff_rfid = get_staff_by_rfid($conn, $_GET['id_rfid']);
if(empty($data_staff_rfid)){
    $data_json = json_encode(array('code'=>'013', 'message'=>'Invalid RFID'), JSON_UNESCAPED_UNICODE);
}

$data_activity = get_active_activity_by_id_and_machine(
    $conn,
    $_GET['id_activity'],
    $_GET['activity_type'],
    $data_staff_rfid['id_staff'],
    $_GET['id_mc']);

print_r($data_activity);

require "contact.php";
require 'terminate.php';

