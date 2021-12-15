<?php
require 'establish.php';

$sql = "UPDATE wip_backflush SET qty_process=" . $_GET['qty'] . " WHERE id_wip_backflush=(SELECT id_wip FROM machine WHERE id_mc='" . $_GET['id_mc'] . "')";
if ($conn->query($sql) === TRUE) {$error=0;} else {echo "ERROR02";}

$sql = "UPDATE machine SET contact_date=CURRENT_DATE(), contact_time=CURRENT_TIME(), contact_count=" . $_GET['count'] . " WHERE id_mc='" . $_GET['id_mc'] . "'";
if ($conn->query($sql) === TRUE) {$error=0;} else {echo "ERROR03";}

if ($error==0) echo "OK";

require 'terminate.php';