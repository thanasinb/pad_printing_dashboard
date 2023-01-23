<?php
require 'establish.php';
$dt = new DateTime('NOW');
$dt_text = $dt->format('Y-m-d H:i:s');

$sql = "INSERT INTO log (log_datetime, log_description) VALUES ('" . $dt_text . "', '". $_GET['log']. "')";
$conn->query($sql);
echo $sql;
require 'terminate.php';
