<?php
require 'update/establish.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ตรวจสอบเบื้องต้น: ต้องมี qr_code_data และ id_cam เสมอ
    if (!isset($_POST['qr_code_data'], $_POST['id_cam']) ||
        empty(trim($_POST['qr_code_data'])) || empty(trim($_POST['id_cam']))) {
        http_response_code(400);
        echo "Invalid input: QR Code data and id_cam are required.";
        exit;
    }

    $qr_code_data = trim($_POST['qr_code_data']); // Tray ID
    $id_cam       = trim($_POST['id_cam']);
    $timestamp    = date('Y-m-d H:i:s');

    // หากเป็น CamForeman → บันทึกใน special_tray_data
    if ($id_cam === 'CamForeman') {
        $sql = "INSERT INTO special_tray_data (tray_id, id_cam, qty_per_pulse2, timestamp) 
                VALUES (?, ?, 0, ?)
                ON DUPLICATE KEY UPDATE timestamp = VALUES(timestamp), id_cam = VALUES(id_cam)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $qr_code_data, $id_cam, $timestamp);

        if ($stmt->execute()) {
            echo "Tray ID recorded successfully!";
        } else {
            http_response_code(500);
            echo "Error: " . $stmt->error;
        }
        $stmt->close();

    } else {
        // ถ้าไม่ใช่ CamForeman → บันทึกลง qr_data ปกติ
        // 1) ตรวจสอบว่าได้ส่ง description มาหรือไม่
        if (!isset($_POST['description']) || empty(trim($_POST['description']))) {
            http_response_code(400);
            echo "Invalid input: Description is required.";
            exit;
        }

        // 2) ตรวจสอบว่าได้ส่ง id_mc มาหรือไม่ (ถ้าต้องการบังคับ)
        if (!isset($_POST['id_mc']) || empty(trim($_POST['id_mc']))) {
            http_response_code(400);
            echo "Invalid input: id_mc is required.";
            exit;
        }

        $description = trim($_POST['description']);
        $id_mc       = trim($_POST['id_mc']);

        // 3) Insert ลง qr_data พร้อม id_mc
        $sql = "INSERT INTO qr_data (id_cam, id_mc, qr_code_data, description, timestamp) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $id_cam, $id_mc, $qr_code_data, $description, $timestamp);

        if ($stmt->execute()) {
            echo "New record created successfully";
        } else {
            http_response_code(500);
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>