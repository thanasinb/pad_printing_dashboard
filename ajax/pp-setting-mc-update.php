<?php
require '../update/establish.php';

session_start(); // เริ่ม session เพื่อตรวจสอบสิทธิ์การเข้าใช้งาน

// ตรวจสอบสิทธิ์การเข้าใช้งาน
if (!isset($_SESSION['username'])) {
    echo json_encode(array("statusCode" => 403, "message" => "Forbidden"));
    exit();
}

$username = $_SESSION['username'];

// ตรวจสอบว่าต้องมีค่า id_mc เสมอ
if (empty($_POST['id_mc'])) {
    echo json_encode(array("statusCode" => 400, "message" => "Missing id_mc (Machine ID)."), JSON_UNESCAPED_UNICODE);
    exit();
}

$id_mc = $_POST['id_mc'];

// ดึงข้อมูล Machine ปัจจุบันจากฐานข้อมูล
$sql = "SELECT machine.id_mc, machine.id_mc_type, machine.mc_des, machine.id_cam, machine_type.mc_type 
        FROM machine 
        LEFT JOIN machine_type ON machine.id_mc_type = machine_type.id_mc_type
        WHERE machine.id_mc = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id_mc);
$stmt->execute();
$result = $stmt->get_result();
$data_machine = $result->fetch_assoc();
$stmt->close();

// ตรวจสอบว่า Machine ID มีอยู่จริงหรือไม่
if (!$data_machine) {
    echo json_encode(array("statusCode" => 601, "message" => "Machine not found."), JSON_UNESCAPED_UNICODE);
    exit();
}

$updates = [];

// ตรวจสอบและอัปเดต Machine Type
if (isset($_POST['id_mc_type']) && $_POST['id_mc_type'] !== $data_machine['id_mc_type']) {
    $new_mc_type = $_POST['id_mc_type'];

    // ดึงชื่อประเภทเครื่องใหม่
    $stmt = $conn->prepare("SELECT mc_type FROM machine_type WHERE id_mc_type = ?");
    $stmt->bind_param("s", $new_mc_type);
    $stmt->execute();
    $stmt->bind_result($new_mc_type_name);
    $stmt->fetch();
    $stmt->close();

    $sql_update = "UPDATE machine SET id_mc_type=? WHERE id_mc=?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ss", $new_mc_type, $id_mc);
    $stmt_update->execute();

    $updates[] = "แก้ไข Machine Type: {$data_machine['mc_type']} → $new_mc_type_name";
}

// ตรวจสอบและอัปเดต Machine Description
if (isset($_POST['mc_des']) && $_POST['mc_des'] !== $data_machine['mc_des']) {
    $mc_des = $_POST['mc_des'];
    $sql_update = "UPDATE machine SET mc_des=? WHERE id_mc=?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ss", $mc_des, $id_mc);
    $stmt_update->execute();

    $updates[] = "แก้ไข Machine Description: {$data_machine['mc_des']} → $mc_des";
}

// ตรวจสอบและอัปเดต Camera ID
if (isset($_POST['id_cam']) && $_POST['id_cam'] !== $data_machine['id_cam']) {
    $id_cam = $_POST['id_cam'];
    $sql_update = "UPDATE machine SET id_cam=? WHERE id_mc=?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ss", $id_cam, $id_mc);
    $stmt_update->execute();

    $updates[] = "แก้ไข Camera ID: {$data_machine['id_cam']} → $id_cam";
}

if (!empty($updates)) {
    $changeDetails = json_encode($updates, JSON_UNESCAPED_UNICODE);
    $action = "แก้ไขข้อมูล Machine ID: $id_mc";
    $final_action = $action ;

    // บันทึกลง history
    $sql_log = "INSERT INTO history (username, action, details, date_time) VALUES (?, ?, ?, NOW())";
    $stmt_log = $conn->prepare($sql_log);
    $stmt_log->bind_param("sss", $username, $final_action, $changeDetails);
    $stmt_log->execute();
    $stmt_log->close();

    echo json_encode(array("statusCode" => 200, "message" => "Data updated successfully."), JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(array("statusCode" => 204, "message" => "No changes made."), JSON_UNESCAPED_UNICODE);
}

require '../update/terminate.php';
?>
