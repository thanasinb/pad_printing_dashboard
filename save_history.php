<?php
require 'update/establish.php';

// รับค่า action และ count จากคำขอ
$action = $_POST['action'] ?? '';
$count = $_POST['count'] ?? 0;

// สมมุติให้มีการกำหนดค่า username ของผู้ใช้ที่ทำการดาวน์โหลด (ในที่นี้ใช้เป็น 'current_username')
$username = 'current_username'; // คุณสามารถแทนที่ด้วยชื่อผู้ใช้ที่เข้าสู่ระบบจริง

if (!empty($action) && $count > 0) {
    // เพิ่มประวัติการทำงานในฐานข้อมูล
    $datetime = date('Y-m-d H:i:s');
    $history_query = "INSERT INTO history (username, action, date_time) VALUES ('$username', '$action จำนวน: $count', '$datetime')";

    if ($conn->query($history_query) === TRUE) {
        echo "History saved successfully";
    } else {
        echo "Error: " . $conn->error;
    }
}

// ปิดการเชื่อมต่อกับฐานข้อมูล
$conn->close();
?>
