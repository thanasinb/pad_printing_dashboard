<?php
session_start();

// ตรวจสอบว่ามี session ของ username อยู่หรือไม่
if(isset($_SESSION['username'])) {
    // ส่งค่ากลับเพื่อแสดงว่า Session ยังคงอยู่
    echo "session_valid";
} else {
    // ส่งค่ากลับเพื่อแสดงว่า Session หมดอายุ
    echo "session_expired";
}
?>
