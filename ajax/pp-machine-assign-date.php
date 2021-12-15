<?php
require '../update/establish.php';

$sql="UPDATE machine_queue SET comp_date='" . $_POST["comp_date"] . "' ";
$sql=$sql . "WHERE id_machine='" . $_POST["id_machine"] . "'";
$sql=$sql . "AND queue_number=1";
if ($conn->query($sql) === true) {
    echo json_encode(array("statusCode" => 200));
}
else {
    echo json_encode(array("statusCode"=>201));
}

require '../update/terminate.php';
