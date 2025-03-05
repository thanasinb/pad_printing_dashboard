<?php
require '../update/establish.php';

session_start(); // เริ่ม session เพื่อตรวจสอบสิทธิ์การเข้าใช้งาน

// ตรวจสอบสิทธิ์การเข้าใช้งานด้วย username ใน session
if (!isset($_SESSION['username'])) {
    echo json_encode(array("statusCode" => 403, "message" => "Forbidden"));
    exit();
}

$username = $_SESSION['username'];
$box_code = $_GET['box_code'];

// 🔹 ดึงข้อมูล Downtime ปัจจุบันจากฐานข้อมูล
$sql = "SELECT id_code_downtime, code_downtime, des_downtime, des_downtime_thai FROM code_downtime WHERE id_code_downtime=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $box_code);
$stmt->execute();
$result = $stmt->get_result();
$data_code_downtime = $result->fetch_assoc();
$stmt->close();

if (empty($data_code_downtime['id_code_downtime'])) {
    echo json_encode(array("statusCode" => 601, "message" => "Box code not found."), JSON_UNESCAPED_UNICODE);
    exit();
}

$changes = [];
$updates_made = false;

// 🔹 ตรวจสอบและอัปเดต Downtime Code
if ($_GET['downtime_code'] != $data_code_downtime['code_downtime']) {
    $prev_downtime_code = $data_code_downtime['code_downtime'];
    $sql_update = "UPDATE code_downtime SET code_downtime=? WHERE id_code_downtime=?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ss", $_GET['downtime_code'], $box_code);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $changes[] = "แก้ไข Downtime Code: $prev_downtime_code → {$_GET['downtime_code']}";
        $updates_made = true;
    }
    $stmt->close();
}

// 🔹 ตรวจสอบและอัปเดต Description Thai
if ($_GET['des_tha'] != $data_code_downtime['des_downtime_thai']) {
    $prev_des_tha = $data_code_downtime['des_downtime_thai'];
    $sql_update = "UPDATE code_downtime SET des_downtime_thai=? WHERE id_code_downtime=?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ss", $_GET['des_tha'], $box_code);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $changes[] = "แก้ไข Description Thai: $prev_des_tha → {$_GET['des_tha']}";
        $updates_made = true;
    }
    $stmt->close();
}

// 🔹 ตรวจสอบและอัปเดต Description Eng
if ($_GET['des_eng'] != $data_code_downtime['des_downtime']) {
    $prev_des_eng = $data_code_downtime['des_downtime'];
    $sql_update = "UPDATE code_downtime SET des_downtime=? WHERE id_code_downtime=?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ss", $_GET['des_eng'], $box_code);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $changes[] = "แก้ไข Description Eng: $prev_des_eng → {$_GET['des_eng']}";
        $updates_made = true;
    }
    $stmt->close();
}

// 🔹 บันทึกการเปลี่ยนแปลงลงใน history
if ($updates_made) {
    $action = "แก้ไข Downtime Box Code: $box_code";
    $details = json_encode($changes, JSON_UNESCAPED_UNICODE);
    logHistory($username, $action, $details, $conn);
    echo json_encode(array("statusCode" => 200, "message" => "OK."), JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(array("statusCode" => 500, "message" => "No data updated."), JSON_UNESCAPED_UNICODE);
}

// 🔹 ฟังก์ชันสำหรับบันทึกประวัติ
function logHistory($username, $action, $details, $conn) {
    $sql_log_action = "INSERT INTO history (username, action, details, date_time) VALUES (?, ?, ?, NOW())";
    $stmt_log_action = $conn->prepare($sql_log_action);
    $stmt_log_action->bind_param("sss", $username, $action, $details);
    $stmt_log_action->execute();
    $stmt_log_action->close();
}

require '../update/terminate.php';
?>