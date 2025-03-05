<?php
session_start();
require 'update/establish.php';

ini_set('session.gc_maxlifetime', 3600);
$current_time = time();

// ตรวจสอบว่า Session หมดอายุหรือไม่
if (isset($_SESSION['last_activity']) && ($current_time - $_SESSION['last_activity'] > 3600)) {
    $logout_user = $_SESSION['username'] ?? 'Unknown User';

    // บันทึกการ Logout (session expired)
    $stmt = $conn->prepare("INSERT INTO history (username, action, date_time) VALUES (?, 'Logout (session expired)', NOW())");
    $stmt->bind_param("s", $logout_user);
    $stmt->execute();
    $stmt->close();

    // ทำลาย Session และลบ Cookie
    session_unset();
    session_destroy();
    if (isset($_COOKIE['session_token'])) {
        setcookie("session_token", "", time() - 3600, "/");
    }

    // แจ้งเตือนหรือ Redirect
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        echo json_encode(['status' => 'expired', 'message' => 'Session หมดอายุแล้ว']);
        exit();
    } else {
        echo "<script>alert('Session หมดอายุแล้ว กรุณาเข้าสู่ระบบใหม่');</script>";
        echo "<script>window.location.href = 'pp-homepage.php';</script>";
        exit();
    }
}

// ตรวจสอบว่า Token ของ Session และ Cookie ตรงกันหรือไม่
if (!isset($_SESSION['session_token']) || !isset($_COOKIE['session_token']) ||
    $_SESSION['session_token'] !== $_COOKIE['session_token']) {

    session_unset();
    session_destroy();
    setcookie("session_token", "", time() - 3600, "/");

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        echo json_encode(['status' => 'expired', 'message' => 'Token ไม่ตรงกัน กรุณาเข้าสู่ระบบใหม่']);
    } else {
        echo "<script>alert('Session หรือ Token ไม่ถูกต้อง กรุณาเข้าสู่ระบบใหม่');</script>";
        echo "<script>window.location.href = 'pp-homepage.php';</script>";
    }
    exit();
}

// อัปเดตกิจกรรมล่าสุด (เฉพาะเมื่อไม่หมดอายุและ Token ตรงกัน)
$_SESSION['last_activity'] = $current_time;
?>