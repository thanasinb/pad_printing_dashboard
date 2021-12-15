<?php
require 'update/establish.php';
$sql = "SELECT * FROM operation INNER JOIN machine_type ON operation.id_mc_type = machine_type.id_mc_type";
$result = $conn->query($sql);
require 'update/terminate.php';
while($row = $result->fetch_assoc()){
    echo "<tr>";
    echo "<td>" . $row["id_op"] . "</td>";
    echo "<td>" . $row["op_des"] . "</td>";
    echo "<td>" . $row["work_ctr"] . "</td>";
    echo "<td>" . $row["date_start_op"] . "</td>";
    echo "<td>" . $row["date_due_op"] . "</td>";
    echo "<td>" . $row["exp_date_finish"] . "</td>";
    echo "<td>" . substr($row["exp_time_finish"],0,-3) . "</td>";
    echo "<td>" . $row["std_setup"] . "</td>";
    echo "<td>" . $row["std_run"] . "</td>";
    echo "<td>" . number_format($row["qty_open"]) . "</td>";
    echo "<td>" . substr($row["time_run"],0,-3) . "</td>";
    echo "<td>" . $row["yield"] . "</td>";
    echo "<td>" . number_format($row["qty_exp1"]) . "</td>";
    echo "<td>" . number_format($row["qty_exp2"]) . "</td>";
    echo "<td>" . $row["mc_type"] . "</td>";
    echo "</tr>";
}
?>