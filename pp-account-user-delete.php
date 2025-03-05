<?php
session_start();
require 'update/establish.php';

// 🔹 Debug ค่าเซสชัน
file_put_contents("debug_log.txt", "Session Data: " . print_r($_SESSION, true) . "\n", FILE_APPEND);

// ตรวจสอบว่าเซสชันมีค่า `username` หรือไม่
if (!isset($_SESSION['username'])) {
    echo json_encode(["statusCode" => 401, "message" => "ไม่ได้รับอนุญาต"]);
    exit;
}

$currentUsername = $_SESSION['username']; // ดึงค่า username ของผู้ที่ล็อกอินอยู่
file_put_contents("debug_log.txt", "Current Username: " . $currentUsername . "\n", FILE_APPEND);

// รับค่า id_staff ที่ต้องการลบ และตรวจสอบว่าถูกต้องหรือไม่
$idStaff = filter_input(INPUT_GET, 'id_staff', FILTER_VALIDATE_INT);
if (!$idStaff) {
    echo json_encode(["statusCode" => 400, "message" => "ID ไม่ถูกต้อง"]);
    exit;
}
file_put_contents("debug_log.txt", "Target User ID to Delete: " . $idStaff . "\n", FILE_APPEND);

// ดึง username ของบัญชีที่ต้องการลบ
$stmtTargetUser = $conn->prepare("SELECT username FROM login WHERE id_staff = ?");
$stmtTargetUser->bind_param("i", $idStaff);
$stmtTargetUser->execute();
$resultTargetUser = $stmtTargetUser->get_result();
$targetUsername = ($row = $resultTargetUser->fetch_assoc()) ? $row['username'] : null;

// ตรวจสอบว่ามีบัญชีที่ต้องการลบอยู่จริงหรือไม่
if (!$targetUsername) {
    echo json_encode(["statusCode" => 404, "message" => "ไม่พบบัญชีที่ต้องการลบ"]);
    exit;
}
file_put_contents("debug_log.txt", "Target Username: " . $targetUsername . "\n", FILE_APPEND);

// ป้องกันไม่ให้ลบบัญชีตัวเอง (ตรวจสอบ username)
if ($targetUsername === $currentUsername) {
    echo json_encode(["statusCode" => 403, "message" => "ไม่สามารถลบบัญชีของตนเองได้"]);
    exit;
}

// 🔹 ฟังก์ชันสำหรับบันทึกประวัติการลบ
function logHistory($username, $action, $conn) {
    $sql_log = "INSERT INTO history (username, action, date_time) VALUES (?, ?, NOW())";
    $stmt_log = $conn->prepare($sql_log);
    $stmt_log->bind_param("ss", $username, $action);
    $stmt_log->execute();
    $stmt_log->close();
}

// ลบจากตาราง login
$stmtDelete = $conn->prepare("DELETE FROM login WHERE id_staff = ?");
$stmtDelete->bind_param("i", $idStaff);

if ($stmtDelete->execute() && $stmtDelete->affected_rows > 0) {
    // 🔹 บันทึกประวัติการลบบัญชี
    $action = "ลบบัญชี: " . $targetUsername;
    logHistory($currentUsername, $action, $conn);

    echo json_encode(["statusCode" => 200, "message" => "ลบบัญชีผู้ใช้เรียบร้อยแล้ว"]);
} else {
    echo json_encode(["statusCode" => 500, "message" => "ไม่สามารถลบบัญชีได้"]);
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
