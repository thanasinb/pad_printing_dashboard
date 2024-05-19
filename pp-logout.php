<?php
session_start();

// เชื่อมต่อกับไฟล์เชื่อมต่อฐานข้อมูล
require 'update/establish.php';

// ตรวจสอบว่ามีการล็อกอินเมื่อมี session ของ username อยู่
if(isset($_SESSION['username'])) {
    // รับค่า username จาก session
    $logout_user = $_SESSION['username'];

    // SQL query เพื่อเพิ่มรายการประวัติการล็อกเอาท์ลงในฐานข้อมูล history
    $history_sql = "INSERT INTO history (username, action, date_time)
                    VALUES ('$logout_user', 'Logout', NOW())";

    // ทำการ execute SQL query
    $conn->query($history_sql);

    // ล้าง session ออกเมื่อล็อกเอาท์
    session_unset();
    session_destroy();
}

// กลับไปยังหน้า pp-login.php
header("Location: pp-homepage.php");
exit();
?>
