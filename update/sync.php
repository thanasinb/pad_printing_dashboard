<?php
require 'establish.php';

//$sql = "SELECT id_staff,name_first,name_last, id_role as role FROM staff WHERE id_rfid='" . $_GET["id_rfid"] . "'";
//$result = $conn->query($sql);
//$data_staff = $result->fetch_assoc();
//$id_role = intval($data_staff["role"]);

$sql = "SELECT id_task, id_job, item_no, operation, op_name, qty_order, qty_comp, qty_open FROM planning WHERE id_mc = '" . $_GET["id_mc"] . "'AND queue=1";
$result = $conn->query($sql);
$data_job = $result->fetch_assoc();

if (!empty($data_job)){
    $data_json = json_encode($data_job, JSON_UNESCAPED_UNICODE);
}else{
    $data_json = json_encode(array("code"=>"NOOP"), JSON_UNESCAPED_UNICODE);
}
require 'contact.php';
require 'terminate.php';
print_r($data_json);
