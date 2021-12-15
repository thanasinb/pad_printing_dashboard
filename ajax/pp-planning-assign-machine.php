<?php
require '../update/establish.php';

if(strcmp($_POST["id_mc"],"Unassign")==0){
    $sql1="UPDATE planning SET ";
    $sql1=$sql1. "id_mc = '', ";
    $sql1=$sql1. "queue=0 ";
    $sql1=$sql1. "WHERE id_task=" . $_POST["id_task"];
}else{
    $sql1="UPDATE planning SET ";
    $sql1=$sql1. "id_mc = '" . $_POST["id_mc"] . "', ";
    $sql1=$sql1. "queue=(SELECT IFNULL(MAX(queue),0) FROM planning WHERE id_mc = '" . $_POST["id_mc"] . "')+1 ";
    $sql1=$sql1. "WHERE id_task=" . $_POST["id_task"];
}
//echo json_encode(array("sql"=>$sql));
if ($conn->query($sql1) === true) {
    echo json_encode(array("statusCode1" => 200));
}
else {
    echo json_encode(array("statusCode1"=>201));
}

if($_POST["id_mc_old"]!=0){
    $sql2="UPDATE planning SET queue=queue-1 WHERE ";
    $sql2=$sql2. "queue>" . $_POST["queue"] . " AND ";
    $sql2=$sql2. "id_mc='" . $_POST["id_mc_old"] . "'";
}
//echo json_encode(array("sql"=>$sql2));
if ($conn->query($sql2) === true) {
    echo json_encode(array("statusCode2" => 200));
}
else {
    echo json_encode(array("statusCode2"=>201));
}

require '../update/terminate.php';
