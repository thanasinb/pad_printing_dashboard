<?php
session_start();

// ลบ session ออกจากระบบ
session_destroy();

// ลิ้งก์ไปยังหน้า login.php หรือหน้าที่ต้องการให้ผู้ใช้ล็อกอินใหม่
header("Location: pp-homepage.php");
exit();
?>
