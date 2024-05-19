<?php
session_start();

// กำหนดเวลา timeout ของ session เป็น 1 ชั่วโมง (3600 วินาที)
ini_set('session.gc_maxlifetime', 3600);

// ตรวจสอบเวลาปัจจุบันกับเวลา session ที่บันทึกไว้
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 3600)) {
    // หากเวลาปัจจุบันมากกว่าเวลา last activity + 3600 วินาที (1 ชั่วโมง)
    session_unset(); // ลบข้อมูลทั้งหมดใน session
    session_destroy(); // ทำลาย session
    header("Location: pp-login.php"); // เปลี่ยนเส้นทางไปยังหน้า login
    exit();
}

// อัปเดตเวลา last activity เป็นเวลาปัจจุบัน
$_SESSION['last_activity'] = time();

// ตัวอย่างการตั้งค่า username หลังจากการตรวจสอบ session
if (!isset($_SESSION['username'])) {
    header("Location: pp-login.php");
    exit();
}

// รหัสส่วนที่เหลือของคุณ
?>
