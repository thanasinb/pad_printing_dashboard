<?php
require 'establish.php';
require 'lib_get_staff_by_rfid.php';
require 'lib_get_staff_by_id.php';
require 'lib_get_staff_from_machine_queue.php';
require 'lib_get_planning.php';
require 'lib_get_qty_shif.php';
require 'lib_get_active_activity_by_id_and_machine.php';
require 'lib_add_log.php';

add_log($conn, basename($_SERVER['REQUEST_URI']));

$data_staff_rfid = get_staff_by_rfid($conn, $_GET['id_rfid']);
if(empty($data_staff_rfid)){
    $data_json = json_encode(array('code'=>'004', 'message'=>'Invalid RFID'), JSON_UNESCAPED_UNICODE);
}
else{
    $data_machine_queue = get_staff_from_machine_queue($conn, $_GET['id_mc']);
    if($data_machine_queue['id_task'] == null){
        $data_json = json_encode(array('code'=>'003', 'message'=>'Machine has no task or Machine ID is invalid'), JSON_UNESCAPED_UNICODE);
    }else{
        if($data_machine_queue['id_staff'] == null){
            $data_planning = get_planning($conn, $data_machine_queue['id_task']);
            $qty_shif = get_qty_shif(
                $conn,
                $data_machine_queue['id_task'],
                $_GET['id_mc']);
            $data_json = json_encode(array_merge($data_staff_rfid, $data_planning, array('qty_shif'=>$qty_shif)), JSON_UNESCAPED_UNICODE);
        }
        else{
            $data_json = json_encode(array('code'=>'026', 'message'=>'This machine is currently in occupied by staff ID: ' . $data_machine_queue['id_staff']), JSON_UNESCAPED_UNICODE);
        }
    }
}

require "contact.php";
require 'terminate.php';

print_r($data_json);
