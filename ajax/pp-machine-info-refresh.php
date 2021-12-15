<?php
require '../update/establish.php';

$sql = "SELECT * FROM activity WHERE status_work=1 AND id_machine='" . $_POST["id_mc"] . "'";
$result = $conn->query($sql);
$db_activity = $result->fetch_assoc();

$sql = "SELECT * FROM `planning` WHERE queue <> 0 AND id_mc='" . $_POST["id_mc"] . "' ORDER BY queue ASC";
$result = $conn->query($sql);
$db_planning = $result->fetch_assoc();

echo json_encode(array_merge($db_activity, $db_planning));

require '../update/terminate.php';
