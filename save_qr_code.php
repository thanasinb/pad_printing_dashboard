<?php
require 'update/establish.php'; // เชื่อมต่อกับฐานข้อมูล MySQL

session_start(); // เริ่ม session เพื่อตรวจสอบสิทธิ์การเข้าใช้งาน

// ตรวจสอบสิทธิ์การเข้าใช้งานด้วย username ใน session
if (!isset($_SESSION['username'])) {
    http_response_code(403); // ส่งรหัส HTTP 403 Forbidden
    exit();
}

$username = $_SESSION['username'];

if ($_POST['action'] == 'download') {
    $downloadCount = intval($_POST['count']);
    $downloadedCodes = json_decode($_POST['qr_codes'], true); // รับข้อมูล QR Code values

    // บันทึกประวัติการใช้งาน
    $action = "Download QR Code: " . implode(', ', $downloadedCodes);
    $sql_history = "INSERT INTO history (username, action, date_time, qr_codes) VALUES (?, ?, NOW(), ?)";
    $stmt_history = $conn->prepare($sql_history);
    $stmt_history->bind_param("sss", $username, $action, json_encode($downloadedCodes)); // บันทึก QR Code values ในประวัติ
    $stmt_history->execute();
    http_response_code(200); // ส่งรหัส HTTP 200 OK
    $stmt_history->close();
    $conn->close();
    exit();
}
// รับค่า QR Code และภาพ QR Code จาก AJAX request
$qrCodeValue = $_POST['qr_code_value'];
$qrCodeImage = $_POST['qr_code_image'];

// ดึง id_staff จากฐานข้อมูลโดยใช้ username จาก session
$sql_select = "SELECT id_staff FROM login WHERE username = ?";
$stmt_select = $conn->prepare($sql_select);
$stmt_select->bind_param("s", $username);
$stmt_select->execute();
$result = $stmt_select->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $id_staff = $row['id_staff'];

    // ตรวจสอบว่ามี QR Code นี้ในฐานข้อมูลแล้วหรือไม่
    $sql_check = "SELECT COUNT(*) as count FROM qrcodes WHERE id_qr = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $qrCodeValue);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $row_check = $result_check->fetch_assoc();

    if ($row_check['count'] == 0) {
        // ถ้ายังไม่มีให้ทำการบันทึกลงฐานข้อมูล
        $sql_insert = "INSERT INTO qrcodes (id_qr, qr_code_image_path, gen_date, id_staff) 
                       VALUES (?, ?, NOW(), ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("sss", $qrCodeValue, $qrCodeImage, $id_staff);
        if ($stmt_insert->execute()) {
            // บันทึกประวัติการใช้งาน
            $action = "บันทึก QR Code: $qrCodeValue";
            $sql_history = "INSERT INTO history (username, action, date_time) VALUES (?, ?, NOW())";
            $stmt_history = $conn->prepare($sql_history);
            $stmt_history->bind_param("ss", $username, $action);
            $stmt_history->execute();

            http_response_code(200); // ส่งรหัส HTTP 200 OK
        } else {
            http_response_code(500); // ส่งรหัส HTTP 500 Internal Server Error
        }
        $stmt_insert->close();
    } else {
        http_response_code(409); // ส่งรหัส HTTP 409 Conflict (รหัสซ้ำ)
    }
    $stmt_check->close();
} else {
    http_response_code(404); // ส่งรหัส HTTP 404 Not Found (ไม่พบ id_staff)
}

$stmt_select->close();
$conn->close();
?>
