<?php
require 'establish.php';

$sql = "SELECT id_staff, name_first, name_last, id_role as role FROM staff WHERE id_rfid='" . $_GET["id_rfid"] . "'";
$result = $conn->query($sql);
$data_staff = $result->fetch_assoc();
$data_staff["role"]=intval($data_staff["role"]);

$sql = "SELECT id_task, id_staff FROM machine_queue WHERE id_machine = '" . $_GET["id_mc"] . "' AND queue_number=1";
$result = $conn->query($sql);
$data_machine_queue = $result->fetch_assoc();

// IF THE STAFF ID DOES NOT EXIST IN THE QUEUE, REPLY THE STAFF AND TASK INFO
if($data_machine_queue['id_staff'] == null){
    $sql = "SELECT id_task, id_job, item_no, operation, planning.op_color, planning.op_side, op_des AS op_name, qty_order, qty_comp, qty_open, divider as multiplier FROM planning INNER JOIN divider ON (planning.op_color=divider.op_color AND planning.op_side=divider.op_side) WHERE id_task=" . $data_machine_queue['id_task'];
    $result = $conn->query($sql);
    $data_planning = $result->fetch_assoc();
    $data_planning['item_no'] = substr($data_planning['item_no'], 0, -3);
    $data_json = json_encode(array_merge($data_staff, $data_planning), JSON_UNESCAPED_UNICODE);
}else{
    $data_json = json_encode(array("code"=>"026", "message"=>"This machine is currently in occupied by staff ID: " . $data_machine_queue['id_staff']), JSON_UNESCAPED_UNICODE);
}

require "contact.php";
require 'terminate.php';

print_r($data_json);
