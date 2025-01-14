<?php
session_start();
require 'update/establish.php';

ini_set('session.gc_maxlifetime', 3600);

// ตรวจสอบว่า Session หมดอายุหรือยัง
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 3600)) {
    $logout_user = $_SESSION['username'] ?? 'Unknown User'; // เก็บ username ก่อนทำลาย Session

    // บันทึก Logout (session expired)
    $stmt = $conn->prepare("INSERT INTO history (username, action, date_time) VALUES (?, 'Logout (session expired)', NOW())");
    $stmt->bind_param("s", $logout_user);
    $stmt->execute();
    $stmt->close();

    // ทำลาย Session
    session_unset();
    session_destroy();

    // ลบ Cookie
    if (isset($_COOKIE['session_token'])) {
        setcookie("session_token", "", time() - 3600, "/");
    }

    // ตรวจสอบว่าเป็น AJAX Request หรือไม่
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        echo json_encode(['status' => 'expired', 'message' => 'Session หมดอายุแล้ว']);
        exit();
    }

    // Redirect สำหรับคำขอปกติ
    echo "<script>alert('Session ของคุณหมดอายุแล้ว กรุณาเข้าสู่ระบบอีกครั้ง');</script>";
    echo "<script>window.location.href = 'pp-logout-session.php';</script>";
    exit();
}

// อัปเดตกิจกรรมล่าสุด
$_SESSION['last_activity'] = time();
?>