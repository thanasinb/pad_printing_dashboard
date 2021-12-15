<?php
require 'update/establish.php';
$sql = "SELECT id_mc_type,mc_type FROM machine_type";
$result = $conn->query($sql);
require 'update/terminate.php';
while($row = $result->fetch_assoc())
    echo "<option value='" . $row["id_mc_type"] . "'>" . $row["mc_type"] . "</option>>";
?>
