<?php
session_start();

// ตรวจสอบ Session ว่ามีผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['username'])) {
    // ถ้ายังไม่ล็อกอิน ให้เปลี่ยนเส้นทางไปยังหน้า pp-login.php
    header("Location: ./pp-login.php");
    exit(); // จบการทำงานของสคริปต์
}
require 'update/establish.php';
// ตรวจสอบว่ามีการล็อกอินแล้วหรือไม่
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username']; // รับชื่อผู้ใช้จาก session

    // คำสั่ง SQL สำหรับดึงชื่อผู้ใช้จากฐานข้อมูล
    $sql = "SELECT staff.name_first
                FROM login
                INNER JOIN staff ON login.id_staff = staff.id_staff
                WHERE login.username = '$username'";
    $result = $conn->query($sql);

    // ตรวจสอบผลลัพธ์
    if ($result->num_rows > 0) {
        // แสดงชื่อผู้ใช้
        while($row = $result->fetch_assoc()) {
            $name = $row["name_first"];
            echo '<div class="dropdown-user-details-name">' . $name . '</div>';
        }
    }
} else {
    $name = "Welcome"; // กำหนดค่า $name เป็น "Welcome" ในกรณีที่ไม่มีการล็อกอิน
}
?>