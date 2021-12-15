<?php
require 'establish.php';
$sql = "SELECT id_staff,name_first,name_last FROM staff WHERE id_rfid='" . $_GET['id_rfid'] . "'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$data = json_encode($row, JSON_UNESCAPED_UNICODE);
print_r($data);
require 'terminate.php';

