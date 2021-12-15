<?php
require '../update/establish.php';

$sub_sql = "SELECT id_task FROM machine_queue WHERE id_machine='" . $_POST["id_mc"] . "' AND queue_number=1";

$sql="UPDATE planning SET multiplier=" . $_POST["m"] . " ";
$sql=$sql . "WHERE id_task=(" . $sub_sql . ")";
if ($conn->query($sql) === true) {
    echo json_encode(array("statusCode" => $sql));
}
else {
    echo json_encode(array("statusCode"=>201));
}

require '../update/terminate.php';
