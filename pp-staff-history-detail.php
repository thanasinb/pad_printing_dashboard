<?php
require 'update/establish.php';

if (isset($_GET['id'])) {
    $id_staff = $_GET['id'];

    $sql = "SELECT * FROM history WHERE action LIKE ? ORDER BY date_time DESC";
    $stmt = $conn->prepare($sql);
    $param = "%$id_staff%";
    $stmt->bind_param("s", $param);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<h2>รายละเอียดการแก้ไขพนักงาน</h2>";

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='card mb-3'>";
            echo "<div class='card-header bg-primary text-white'>". $row['date_time'] ."</div>";
            echo "<div class='card-body'>";
            echo "<p><strong>รายละเอียด:</strong> " . $row['detail'] . "</p>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p class='text-center'>ไม่มีประวัติการแก้ไข</p>";
    }

    $stmt->close();
} else {
    echo "<p class='text-center'>ไม่พบข้อมูลพนักงาน</p>";
}

$conn->close();
?>