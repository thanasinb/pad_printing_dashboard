<?php
require 'update/establish.php'; // เชื่อมต่อฐานข้อมูล
session_start(); // เริ่ม session เพื่อตรวจสอบสิทธิ์การเข้าใช้งาน

// ตรวจสอบสิทธิ์การเข้าใช้งานด้วย username ใน session
if (!isset($_SESSION['username'])) {
    http_response_code(403); // ส่งรหัส HTTP 403 Forbidden
    exit();
}

$username = $_SESSION['username'];

// 📌 ตรวจสอบว่าการร้องขอเป็นการดาวน์โหลด QR Code หรือไม่
if ($_POST['action'] == 'download') {
    $downloadedCodes = json_decode($_POST['qr_codes'], true);

    if (empty($downloadedCodes)) {
        http_response_code(400);
        exit("❌ No QR Codes provided.");
    }

    $sql_select = "SELECT id_staff FROM login WHERE username = ?";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param("s", $username);
    $stmt_select->execute();
    $result = $stmt_select->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_staff = $row['id_staff'];

        foreach ($downloadedCodes as $qrCode) {
            $action = "Download QR Code: " . $qrCode;
            $sql_history = "INSERT INTO history (username, action, date_time) VALUES (?, ?, NOW())";
            $stmt_history = $conn->prepare($sql_history);
            $stmt_history->bind_param("ss", $username, $action);
            $stmt_history->execute();
            $stmt_history->close();
        }

        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Download history saved."]);
    } else {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "User not found."]);
    }

    $stmt_select->close();
    $conn->close();
    exit();
}

// 📌 รับค่าจาก AJAX request
$qrCodeValue = $_POST['qr_code_value'] ?? '';
$qrCodeBase64 = $_POST['qr_code_image'] ?? '';

// 📌 ตรวจสอบว่าค่าที่รับมาถูกต้องหรือไม่
if (empty($qrCodeValue) || empty($qrCodeBase64)) {
    http_response_code(400); // Bad Request
    exit("❌ Missing QR Code data.");
}

// 📌 ดึง id_staff จากฐานข้อมูล
$sql_select = "SELECT id_staff FROM login WHERE username = ?";
$stmt_select = $conn->prepare($sql_select);
$stmt_select->bind_param("s", $username);
$stmt_select->execute();
$result = $stmt_select->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $id_staff = $row['id_staff'];

    // 📌 ตรวจสอบว่า QR Code มีอยู่แล้วหรือไม่
    $sql_check = "SELECT id_qr FROM qrcodes WHERE id_qr = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $qrCodeValue);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows == 0) {
        // 📌 ถ้ายังไม่มี ให้ทำการบันทึก QR Code เป็นไฟล์
        $uploadDir = 'uploads/qrcodes/';

        // สร้างโฟลเดอร์ถ้ายังไม่มี
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // แปลง Base64 เป็นไฟล์ภาพ
        $qrCodeData = explode(',', $qrCodeBase64);
        if (count($qrCodeData) === 2) {
            $qrCodeImage = base64_decode($qrCodeData[1]); // แปลง Base64 เป็น Binary
        } else {
            http_response_code(400);
            exit("❌ Invalid Base64 format.");
        }

        // กำหนดชื่อไฟล์ (ใช้ ID QR Code + .png)
        $filePath = $uploadDir . $qrCodeValue . '.png';

        // บันทึกไฟล์
        if (!file_put_contents($filePath, $qrCodeImage)) {
            http_response_code(500);
            exit("❌ Failed to save QR Code image.");
        }

        // 📌 บันทึก Path ของไฟล์ QR Code ลงฐานข้อมูล
        $sql_insert = "INSERT INTO qrcodes (id_qr, qr_code_image_path, gen_date, id_staff) VALUES (?, ?, NOW(), ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("sss", $qrCodeValue, $filePath, $id_staff);

        if ($stmt_insert->execute()) {
            // 📌 บันทึกประวัติการบันทึก QR Code
            $action = "บันทึก QR Code: $qrCodeValue";
            $details = json_encode([
                "qr_code" => $qrCodeValue,
                "qr_code_image" => $filePath
            ], JSON_UNESCAPED_UNICODE);

            $sql_history = "INSERT INTO history (username, action, details, date_time) VALUES (?, ?, ?, NOW())";
            $stmt_history = $conn->prepare($sql_history);
            $stmt_history->bind_param("sss", $username, $action, $details);
            $stmt_history->execute();
            $stmt_history->close();

            echo "<script>alert('Success: QR Code ถูกบันทึกเรียบร้อยแล้ว'); window.location.href='pp-qr-add.php?message=success';</script>";
        } else {
            echo "<script>alert('Error: ไม่สามารถบันทึก QR Code ได้'); window.location.href='pp-qr-add.php?message=error';</script>";
        }
        $stmt_insert->close();
    } else {
        echo "<script>alert('Error: QR Code นี้มีอยู่แล้วในระบบ'); window.location.href='pp-qr-add.php?message=duplicate';</script>";
    }
    $stmt_check->close();
} else {
    echo "<script>alert('Error: ไม่พบข้อมูลพนักงาน'); window.location.href='pp-qr-add.php?message=user_not_found';</script>";
}

$stmt_select->close();
$conn->close();
?>