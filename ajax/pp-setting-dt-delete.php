<?php
require '../update/establish.php';

$box_code = $_GET['box_code'] ?? null;


session_start(); // เริ่ม session เพื่อตรวจสอบสิทธิ์การเข้าใช้งาน

// ตรวจสอบสิทธิ์การเข้าใช้งานด้วย username ใน session
if (!isset($_SESSION['username'])) {
    echo json_encode(array("statusCode" => 403, "message"=>"Forbidden"));
    exit();
}

$username = $_SESSION['username'];

// ตรวจสอบว่ามี downtime code นี้อยู่หรือไม่
$stmt = $conn->prepare("SELECT id_code_downtime FROM code_downtime WHERE id_code_downtime = ?");
$stmt->bind_param("s", $box_code);
$stmt->execute();
$result = $stmt->get_result();
$data_code_downtime = $result->fetch_assoc();

if (!$data_code_downtime) {
    echo json_encode(array("statusCode" => 404, "message" => "Box code not found."), JSON_UNESCAPED_UNICODE);
} else {
    // อัปเดต enable = 0 และ date_setting
    $stmt = $conn->prepare("UPDATE code_downtime SET enable = 0, date_setting = CURRENT_TIMESTAMP WHERE id_code_downtime = ?");
    $stmt->bind_param("s", $box_code);
    if ($stmt->execute()) {
        // เพิ่มการบันทึกประวัติลงใน history
        $action = "ลบ downtime: " . $box_code;
        $username = $_SESSION['username'] ?? 'system'; // ดึงชื่อผู้ใช้งานจากเซสชัน
        $stmt_history = $conn->prepare("INSERT INTO history (username, action, date_time) VALUES (?, ?, CURRENT_TIMESTAMP)");
        $stmt_history->bind_param("ss", $username, $action);
        $stmt_history->execute();

        echo json_encode(array("statusCode" => 200, "message" => "Downtime code disabled successfully."), JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(array("statusCode" => 500, "message" => "Failed to disable downtime code."), JSON_UNESCAPED_UNICODE);
    }
}

require '../update/terminate.php';
?>