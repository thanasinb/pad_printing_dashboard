<?php
require '../update/establish.php';

session_start(); // เริ่ม session เพื่อตรวจสอบสิทธิ์การเข้าใช้งาน

// ตรวจสอบสิทธิ์การเข้าใช้งาน
if (!isset($_SESSION['username'])) {
    echo json_encode(array("statusCode" => 403, "message" => "Forbidden"));
    exit();
}

$username = $_SESSION['username'];

// ตรวจสอบข้อมูลที่ส่งมาว่าไม่เป็นค่าว่าง
if (empty($_GET['id_mc'])) {
    echo json_encode(array("statusCode" => 400, "message" => "Invalid input data."), JSON_UNESCAPED_UNICODE);
    exit();
}

$id_mc = $_GET['id_mc'];

// ตรวจสอบว่า Machine มีอยู่หรือไม่ก่อนลบ
$sql_check = "SELECT id_mc FROM machine WHERE id_mc=?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $id_mc);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows === 0) {
    echo json_encode(array("statusCode" => 601, "message" => "Machine not found."), JSON_UNESCAPED_UNICODE);
} else {
    // ลบ Machine
    $sql_delete = "DELETE FROM machine WHERE id_mc=?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("s", $id_mc);
    $stmt_delete->execute();

    logHistory($username, "ลบ Machine ที่มี ID '{$id_mc}'", $conn);
    echo json_encode(array("statusCode" => 200, "message" => "Machine deleted successfully."), JSON_UNESCAPED_UNICODE);
}

// ฟังก์ชันสำหรับบันทึกประวัติการลบ
function logHistory($username, $action, $conn) {
    $sql_log = "INSERT INTO history (username, action, date_time) VALUES (?, ?, NOW())";
    $stmt_log = $conn->prepare($sql_log);
    $stmt_log->bind_param("ss", $username, $action);
    $stmt_log->execute();
    $stmt_log->close();
}

require '../update/terminate.php';
?>
