<?php
require 'update/establish.php';
$sql = "SELECT * FROM rawmat INNER JOIN color ON rawmat.id_color = color.id_color";
$result = $conn->query($sql);
require 'update/terminate.php';
while($row = $result->fetch_assoc()){
    echo "<tr>";
    echo "<td>" . $row["id_rawmat"] . "</td>";
    echo "<td>" . $row["id_op"] . "</td>";
    echo "<td>" . $row["qty_required"] . "</td>";
    echo "<td>" . $row["qty_allocated"] . "</td>";
    echo "<td>" . $row["qty_picked"] . "</td>";
    echo "<td>" . $row["qty_issued"] . "</td>";
    echo "<td>" . $row["qty_open"] . "</td>";
    echo "<td>" . $row["date_issue_rawmat"] . "</td>";
    echo "<td>" . $row["color_des"] . "</td>";
    echo "<td>" . $row["note_des"] . "</td>";
    echo "</tr>";
}
?>