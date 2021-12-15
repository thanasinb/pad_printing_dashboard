<?php
require 'establish.php';
require 'url.php';

$sql = "INSERT INTO log (log_datetime, log_description) VALUES ('" . $_GET['dt'] . "', '". $_GET['log']. "')";
$conn->query($sql);
require 'terminate.php';

echo "OK";