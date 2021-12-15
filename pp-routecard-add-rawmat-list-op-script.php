<?php
require 'update/establish.php';
$sql = "SELECT id_op FROM operation WHERE id_job=" . $_GET["id_job"];
$result = $conn->query($sql);
require 'update/terminate.php';
while($row = $result->fetch_assoc())
    echo "<option value='" . $row["id_op"] . "'>" . $row["id_op"] . "</option>>";
?>
