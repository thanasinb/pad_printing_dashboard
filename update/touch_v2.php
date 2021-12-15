<?php
require 'establish.php';

$sql = "SELECT id_staff,name_first,name_last, id_role as role FROM staff WHERE id_rfid='" . $_GET["id_rfid"] . "'";
$result = $conn->query($sql);
$data_staff = $result->fetch_assoc();
//$id_role = intval($data_staff["role"]);

$sql = "SELECT id_task, id_job, item_no, operation, op_name, qty_order, qty_comp, qty_open FROM planning WHERE id_mc = '" . $_GET["id_mc"] . "'AND queue=1";
$result = $conn->query($sql);
$data_job = $result->fetch_assoc();

if (!empty($data_staff) && !empty($data_job)){
    $sql = "SELECT id_activity, id_task, id_job, operation FROM activity WHERE status_work<3 AND id_machine='" . $_GET["id_mc"] . "'";
    $result = $conn->query($sql);
    $data_activity = $result->fetch_assoc();

    if (empty($data_activity)){
        $sql = "INSERT INTO activity (id_task, id_job, operation, id_machine, id_staff, status_work, time_start) VALUES (";
        $sql = $sql . $data_job["id_task"] . ",";
        $sql = $sql . "'" . $data_job["id_job"] . "',";
        $sql = $sql . "'" . $data_job["operation"] . "',";
        $sql = $sql . "'" . $_GET["id_mc"] . "',";
        $sql = $sql . "'" . $data_staff["id_staff"] . "',";
        $sql = $sql . "0,";
        $sql = $sql . "CURRENT_TIMESTAMP()";
        $sql = $sql . ")";
        $result = $conn->query($sql);

        $sql = "UPDATE machine SET ";
        $sql = $sql . "time_contact=CURRENT_TIMESTAMP()";
        $sql = $sql . " WHERE id_mc='" . $_GET["id_mc"] . "'";
        $result = $conn->query($sql);

        $data_staff["role"]=intval($data_staff["role"]);
        $data_json = json_encode(array_merge($data_staff,$data_job), JSON_UNESCAPED_UNICODE);
    }else{
        $data_json = json_encode(array("code"=>"008"), JSON_UNESCAPED_UNICODE);
    }
}else{
    $data_json = json_encode(array("code"=>"001"), JSON_UNESCAPED_UNICODE);
}
require 'terminate.php';
print_r($data_json);
