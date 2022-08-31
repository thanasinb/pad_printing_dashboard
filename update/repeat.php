<?php
require 'count.php';
require 'establish.php';
if($_GET["repeat"] == 1){
    $count = 1;
}
elseif($_GET["repeat"] == 2){
    $count = 5;
}
elseif($_GET["repeat"] == 3){
    $count = 10;
}
$sql = "INSERT INTO repeat_activity (id_activity, time_repeat, count_repeat) VALUES (";
$sql = $sql . $data_activity["id_activity"] . ", ";
$sql = $sql . "CURRENT_TIMESTAMP(), ";
$sql = $sql .$count. ")";
$result = $conn->query($sql);

$sql = "UPDATE activity SET num_repeat = num_repeat + ".$count." WHERE id_activity='" . $data_activity["id_activity"] . "' AND id_machine ='".$_GET["id_mc"] . "'AND status_work = 1";
$result = $conn->query($sql);

require 'terminate.php';
?>
