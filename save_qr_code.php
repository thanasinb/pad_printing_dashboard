<?php
require 'update/establish.php'; // เชื่อมต่อกับฐานข้อมูล MySQL

session_start(); // เรียกใช้ session_start() เพื่อเริ่มต้นการใช้งาน session

// ตรวจสอบว่ามี session ของ username หรือไม่ ถ้าไม่มีให้ redirect ไปยังหน้าเข้าสู่ระบบหรือทำการจัดการตามที่คุณต้องการ
if (!isset($_SESSION['username'])) {
    // สามารถเปลี่ยน redirect URL ตามที่ต้องการ
    header("Location: login.php");
    exit(); // หยุดการทำงานของสคริปต์หลังจาก redirect
}

// รับค่า QR Code และ URL ของภาพ QR Code ที่ส่งมาจาก AJAX request
$qrCodeValue = $_POST['qr_code_value'];
$qrCodeImage = $_POST['qr_code_image'];

// รับชื่อผู้ใช้จาก session
$username = $_SESSION['username'];

// สร้างคำสั่ง SQL เพื่อค้นหา id_staff จากฐานข้อมูล
$sql_select = "SELECT id_staff FROM login WHERE username = '$username'";
$stmt_select = $conn->prepare($sql_select);
$stmt_select->bind_param("s", $username);
$stmt_select->execute();
$result = $stmt_select->get_result();

// ตรวจสอบว่ามีผลลัพธ์จาก query หรือไม่
if ($result && $result->num_rows > 0) {
    // ดึงข้อมูล id_staff จากผลลัพธ์ของ query
    $row = $result->fetch_assoc();
    $id_staff = $row['id_staff'];

    $sql_check = "SELECT COUNT(*) as count FROM qrcodes WHERE id_qr = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $qrCodeValue);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $row_check = $result_check->fetch_assoc();

    // เตรียมคำสั่ง SQL เพื่อบันทึกข้อมูล QR Code และ URL ของภาพ QR Code พร้อม id_staff ลงในตาราง qrcodes
    if ($row_check['count'] == 0) {
    $sql = "INSERT INTO qrcodes (id_qr, qr_code_image_path, gen_date, id_staff) 
            VALUES ('$qrCodeValue', '$qrCodeImage', DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i:%s'), '$id_staff')";
        $stmt_insert = $conn->prepare($sql);
        $stmt_insert->bind_param("sss", $qrCodeValue, $qrCodeImage, $id_staff);

        if ($stmt_insert->execute()) {
            http_response_code(200);
        } else {
            http_response_code(500);
        }

        $stmt_insert->close();
    } else {
        http_response_code(409); // Conflict: รหัสซ้ำกัน
    }

    $stmt_check->close();
} else {
    http_response_code(404); // ไม่พบ id_staff
}

$stmt_select->close();
$conn->close();
?>
