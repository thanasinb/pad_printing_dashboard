<?php
session_start();
header('Content-Type: application/json');

// ตรวจสอบว่า Session หมดอายุหรือยัง
if (!isset($_SESSION['username']) || (time() - $_SESSION['last_activity'] > 3600)) {
    session_unset();
    session_destroy();

    if (isset($_COOKIE['session_token'])) {
        setcookie("session_token", "", time() - 3600, "/");
    }

    echo json_encode(['active' => false]); // แจ้งว่า Session หมดอายุ
    exit();
}

$_SESSION['last_activity'] = time(); // อัปเดตเวลาใช้งานล่าสุด
echo json_encode(['active' => true]); // แจ้งว่า Session ยังใช้งานได้