<?php
require 'lib_add_log.php';
require 'establish.php';
//$dt = new DateTime('NOW');
//$dt_text = $dt->format('Y-m-d H:i:s');

//$sql = "INSERT INTO log (log_datetime, log_description) VALUES ('" . $dt_text . "', '". $_GET['log']. "')";
//$conn->query($sql);
//echo $sql;

add_log($conn, basename($_SERVER['REQUEST_URI']));
add_log($conn, basename($_GET['log']));

require 'terminate.php';
