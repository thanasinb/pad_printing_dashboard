<?php
require 'establish.php';

$sql = "SELECT id_staff,name_first,name_last,id_role as role FROM staff WHERE id_rfid='" . $_GET["id_rfid"] . "'";
$result = $conn->query($sql);
$data_staff = $result->fetch_assoc();
//$id_role = intval($data_staff["role"]);

//$sql = "SELECT id_task FROM planning WHERE id_mc = '" . $_GET["id_mc"] . "'AND queue_number=1";
//$query_planning = $conn->query($sql);
//$data_planning = $query_planning->fetch_assoc();

$sql = "SELECT id_task, id_job, item_no, operation, op_name, qty_order, qty_comp, qty_open FROM planning WHERE ";
$sql = $sql . "id_task=(SELECT id_task FROM machine_queue WHERE id_machine = '" . $_GET["id_mc"] . "' AND queue_number=1)";
//echo $sql;
$result = $conn->query($sql);
$data_job = $result->fetch_assoc();

if (!empty($data_staff) && !empty($data_job)){
    $data_staff["role"]=intval($data_staff["role"]);

    if($data_staff["role"]==1){
        $sql = "INSERT INTO activity (id_task, id_job, operation, id_machine, id_staff, status_work, time_start) VALUES (";
        $sql = $sql . $data_job["id_task"] . ",";
        $sql = $sql . "'" . $data_job["id_job"] . "',";
        $sql = $sql . "'" . $data_job["operation"] . "',";
        $sql = $sql . "'" . $_GET["id_mc"] . "',";
        $sql = $sql . "'" . $data_staff["id_staff"] . "',";
        $sql = $sql . "1,";
        $sql = $sql . "CURRENT_TIMESTAMP()";
        $sql = $sql . ")";
        $result = $conn->query($sql);

        $sql = "UPDATE machine_queue SET id_staff='" . $data_staff['id_staff'] . "' WHERE id_machine='" . $_GET["id_mc"] . "' AND queue_number=1";
        $result = $conn->query($sql);
    }
    elseif ($data_staff["role"]==2){
        $sql = "INSERT INTO activity_downtime (id_task, id_machine, id_staff, status_downtime, time_start) VALUES (";
        $sql = $sql . $data_job["id_task"] . ",";
        $sql = $sql . "'" . $_GET["id_mc"] . "',";
        $sql = $sql . "'" . $data_staff["id_staff"] . "',";
        $sql = $sql . "1,";
        $sql = $sql . "CURRENT_TIMESTAMP()";
        $sql = $sql . ")";
        $result = $conn->query($sql);
    }else{
        $data_json = json_encode(array("code"=>"017"), JSON_UNESCAPED_UNICODE);
    }
//    $data_staff["role"]=intval($data_staff["role"]);
    $data_json = json_encode(array_merge($data_staff,$data_job), JSON_UNESCAPED_UNICODE);
}else{
    $data_json = json_encode(array("code"=>"001"), JSON_UNESCAPED_UNICODE);
}
require "contact.php";
require 'terminate.php';
print_r($data_json);
