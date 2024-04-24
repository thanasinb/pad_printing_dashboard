<?php
require 'establish.php';
$dt = new DateTime('NOW');
$dt_text = $dt->format('Y-m-d H:i:s');

$sql = "INSERT INTO log (log_datetime, log_description) VALUES ('" . $dt_text . "', '". $_GET['log']. "')";
$conn->query($sql);

if (strpos($_GET['log'], 'mac=') !== false) {
    $id_machine = substr($_GET['log'], 6, 5);
    header("Location: ./touch-reset.php?dashboard=0&id_mc=" . $id_machine);
    die();
}

require 'terminate.php';
