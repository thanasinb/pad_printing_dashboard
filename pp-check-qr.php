<?php
require 'update/establish.php'; // เชื่อมต่อกับฐานข้อมูล MySQL

// รับค่า QR Code ที่ส่งมาจาก AJAX request
$qrCodeValue = $_POST['qr_code_value'];

// สร้างคำสั่ง SQL เพื่อเช็คว่ามีค่า QR Code อยู่ในฐานข้อมูลหรือไม่
$sql_check = "SELECT * FROM qrcodes WHERE id_qr = ?";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param('s', $qrCodeValue);
$stmt->execute();
$stmt->store_result();

$response = array('exists' => $stmt->num_rows > 0);

$stmt->close();
$conn->close();

// ส่งผลลัพธ์กลับเป็น JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
