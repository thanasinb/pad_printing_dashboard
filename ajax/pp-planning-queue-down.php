<?php
require '../update/establish.php';

$queue = intval($_POST["queue"]);

//$queue = 2

if($queue>0){
    $sql1 = "UPDATE planning SET ";
    $sql1 = $sql1 . "queue=" . $queue . " WHERE ";
    $sql1 = $sql1 . "id_task=(SELECT id_task WHERE ";
    $sql1 = $sql1 . "id_mc='" . $_POST["id_mc"] . "' AND ";
    $sql1 = $sql1 . "queue=" . strval($queue+1) . ")";

    $sql2="UPDATE planning SET ";
    $sql2=$sql2. "queue=" . strval($queue+1) . " ";
    $sql2=$sql2. "WHERE id_task=" . $_POST["id_task"];
}
//echo json_encode(array("sql" => $sql1));

if ($conn->query($sql1) === true) {
    echo json_encode(array("statusCode1" => 200));
}
else {
    echo json_encode(array("statusCode1"=>201));
}

if ($conn->query($sql2) === true) {
    echo json_encode(array("statusCode2" => 200));
}
else {
    echo json_encode(array("statusCode2"=>201));
}

require '../update/terminate.php';
