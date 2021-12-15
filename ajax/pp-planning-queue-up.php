<?php
require '../update/establish.php';

$queue = intval($_POST["queue"]);

//$queue = 2

if($queue>1){
    $sql1 = "UPDATE planning SET ";
    $sql1 = $sql1 . "queue=" . $queue . " WHERE ";
//    $sql1 = $sql1 . "id_task=(SELECT id_task WHERE ";
    $sql1 = $sql1 . "id_mc='" . $_POST["id_mc"] . "' AND ";
    $sql1 = $sql1 . "queue=" . strval($queue-1);
//    $sql1 = $sql1 . ")";

    $sql2 = "UPDATE planning SET ";
    $sql2 = $sql2. "queue=" . strval($queue-1) . " ";
    $sql2 = $sql2. "WHERE id_task=" . $_POST["id_task"];

    $conn->query($sql1);
    $conn->query($sql2);

//    $sql1 = "SELECT id_task FROM planning WHERE ";
//    $sql1 = $sql1 . "id_mc='" . $_POST["id_mc"] . "' AND ";
//    $sql1 = $sql1 . "queue=" . strval($queue-1);
//
//    $sql2 = "SELECT id_task FROM planning WHERE ";
//    $sql2 = $sql2 . "id_mc='" . $_POST["id_mc"] . "' AND ";
//    $sql2 = $sql2 . "queue=" . strval($queue);
//
//    $result_prev = $conn->query($sql1);
//    $result_next = $conn->query($sql2);
//    $row_prev = $result_prev->fetch_assoc();
//    $row_next = $result_next->fetch_assoc();;
//
//    $reply = array("task_prev" => $row_prev["id_task"]);
//    $reply["task_next"] = $row_next["id_task"];
//
//    $reply = array("sql1" => $sql1);
//    $reply["sql2"] = $sql2;
//
//    echo json_encode($reply);
}

require '../update/terminate.php';
