<?php
session_start();

// เชื่อมต่อกับไฟล์เชื่อมต่อฐานข้อมูล
require 'update/establish.php';

// Set session timeout to 1 hour (3600 seconds)
ini_set('session.gc_maxlifetime', 3600);

// Check current time against session's last activity time
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 3600)) {
    // If session has expired
    // รับค่า username จาก session
    $logout_user = $_SESSION['username'];

    // SQL query เพื่อเพิ่มรายการประวัติการล็อกเอาท์ลงในฐานข้อมูล history
    $history_sql = "INSERT INTO history (username, action, date_time)
                    VALUES ('$logout_user', 'Logout', NOW())";

    // ทำการ execute SQL query
    $conn->query($history_sql);
    echo "<script>alert('Session หมดอายุแล้ว');</script>";
    echo "<script>window.location.href = 'pp-logout-session.php';</script>";
    exit();
}

// Update last activity time to the current time
$_SESSION['last_activity'] = time();

// Check if the username session variable is set
if (!isset($_SESSION['username'])) {

    echo "<script>alert('Session หมดอายุแล้วจ้า');</script>";
    echo "<script>window.location.href = 'pp-logout-session.php';</script>";
    exit();
}

// Your remaining code
?>
