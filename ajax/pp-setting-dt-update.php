<?php
require '../update/establish.php';

session_start(); // เริ่ม session เพื่อตรวจสอบสิทธิ์การเข้าใช้งาน

// ตรวจสอบสิทธิ์การเข้าใช้งานด้วย username ใน session
if (!isset($_SESSION['username'])) {
    echo json_encode(array("statusCode" => 403, "message"=>"Forbidden"));
    exit();
}

$username = $_SESSION['username'];

// Check if box_code exists
$sql = "SELECT id_code_downtime, code_downtime, des_downtime, des_downtime_thai FROM code_downtime WHERE id_code_downtime=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_GET['box_code']);
$stmt->execute();
$result = $stmt->get_result();
$data_code_downtime = $result->fetch_assoc();

if (empty($data_code_downtime['id_code_downtime'])){
    echo json_encode(array("statusCode" => 601, "message"=>"Box code not found."), JSON_UNESCAPED_UNICODE);
} else {
    // Initialize action variable
    $action = "";

    // Check and update Downtime Code
    if ($_GET['downtime_code'] != $data_code_downtime['code_downtime']) {
        $prev_downtime_code = $data_code_downtime['code_downtime'];
        $sql_update_downtime_code = "UPDATE code_downtime SET code_downtime=? WHERE id_code_downtime=?";
        $stmt_update_downtime_code = $conn->prepare($sql_update_downtime_code);
        $stmt_update_downtime_code->bind_param("ss", $_GET['downtime_code'], $_GET['box_code']);
        $stmt_update_downtime_code->execute();
        if ($stmt_update_downtime_code->affected_rows > 0) {
            $action = "แก้ไข Downtime Code: {$prev_downtime_code} เป็น {$_GET['downtime_code']}";
        }
        $stmt_update_downtime_code->close();
    }

    // Check and update description Tha
    if ($_GET['des_tha'] != $data_code_downtime['des_downtime_thai']) {
        $prev_des_tha = $data_code_downtime['des_downtime_thai'];
        $sql_update_des_tha = "UPDATE code_downtime SET des_downtime_thai=? WHERE id_code_downtime=?";
        $stmt_update_des_tha = $conn->prepare($sql_update_des_tha);
        $stmt_update_des_tha->bind_param("ss", $_GET['des_tha'], $_GET['box_code']);
        $stmt_update_des_tha->execute();
        if ($stmt_update_des_tha->affected_rows > 0) {
            $action .= (!empty($action) ? ' ' : '') . "แก้ไข Description Tha: {$prev_des_tha} เป็น {$_GET['des_tha']}";
        }
        $stmt_update_des_tha->close();
    }

    // If action was performed, log it to history
    if (!empty($action)) {
        $sql_log_action = "INSERT INTO history (username, action) VALUES (?, ?)";
        $stmt_log_action = $conn->prepare($sql_log_action);
        $stmt_log_action->bind_param("ss", $username, $action);
        $stmt_log_action->execute();
        if ($stmt_log_action->affected_rows > 0) {
            echo json_encode(array("statusCode" => 200, "message"=>"OK."), JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(array("statusCode" => 500, "message"=>"Failed to log action."), JSON_UNESCAPED_UNICODE);
        }
        $stmt_log_action->close();
    } else {
        echo json_encode(array("statusCode" => 500, "message"=>"No data updated."), JSON_UNESCAPED_UNICODE);
    }
}

require '../update/terminate.php';
?>
