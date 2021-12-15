<?php
require 'update/establish.php';
$sql = "SELECT * FROM job";
$result = $conn->query($sql);
require 'update/terminate.php';
while($row = $result->fetch_assoc()){
    echo "<tr>";
    echo "<td><a href='pp-routecard.php?id_job=" .$row["id_job"]. "'>" . $row["id_job"] . "</a></td>";
    echo "<td>" . $row["work_order"] . "</td>";
    echo "<td>" . $row["item_no"] . "</td>";
    echo "<td>" . $row["item_des"] . "</td>";
    echo "<td>" . $row["sales_job"] . "</td>";
    echo "<td>" . number_format($row["qty_order"]) . "</td>";
    echo "<td>" . number_format($row["qty_completed"]) . "</td>";
    echo "<td>" . number_format($row["qty_rejected"]) . "</td>";
    echo "<td>" . $row["date_order_job"] . "</td>";
    echo "<td>" . $row["date_release_job"] . "</td>";
    echo "<td>" . $row["date_due_job"] . "</td>";
    echo "<td>" . $row["prod_line"] . "</td>";
    echo "<td>" . $row["prod_rate"] . "</td>";
    echo "</tr>";
}
?>
