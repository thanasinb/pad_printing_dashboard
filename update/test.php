<?php
require 'establish.php';

$sql = "SELECT id_wip,contact_date,contact_time,contact_count FROM machine WHERE id_mc='02-01'";
//if ($conn->query($sql) === TRUE) {$error=0;} else {echo "ERROR02";}
$result = $conn->query($sql);
$row = $result->fetch_assoc();
echo "Contact date:" . $row["contact_date"] . "<br>";
echo "Contact time:" . $row["contact_time"] . "<br>";
echo "Contact count:" . $row["contact_count"] . "<br>";
$sql = "SELECT qty_process,qty_accum FROM wip_backflush WHERE id_wip_backflush=" . $row["id_wip"];
$result = $conn->query($sql);
$row = $result->fetch_assoc();
echo "Qty process:" . $row["qty_process"] . "<br>";
echo "Qty accum:" . $row["qty_accum"];
require 'terminate.php';

