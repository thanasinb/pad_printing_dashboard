<?php
require '../update/establish.php'; // เชื่อมต่อฐานข้อมูล
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(array("statusCode" => 403, "message" => "Unauthorized access."), JSON_UNESCAPED_UNICODE);
    exit();
}

$username = $_SESSION['username'];

// ตรวจสอบค่าที่ส่งมา
if (isset($_POST['id_job'], $_POST['operation'], $_POST['machine'], $_POST['date_due'])) {
    $id_job = $conn->real_escape_string($_POST['id_job']);
    $operation = $conn->real_escape_string($_POST['operation']);
    $machine = $conn->real_escape_string($_POST['machine']);
    $date_due = $conn->real_escape_string($_POST['date_due']);

    // 🔹 ดึงข้อมูลก่อนลบ เพื่อนำไปบันทึกประวัติ
    $sql_select = "SELECT work_order, item_no, op_color, op_side FROM planning WHERE id_job = ? AND operation = ? AND machine = ? AND date_due = ?";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param("ssss", $id_job, $operation, $machine, $date_due);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    $job_info = $result->fetch_assoc();
    $stmt_select->close();

    if (!$job_info) {
        echo json_encode(array("statusCode" => 404, "message" => "Job row not found."), JSON_UNESCAPED_UNICODE);
        exit();
    }

    // 🔹 ลบเฉพาะแถวที่มี `id_job`, `operation`, `machine`, `date_due`
    $sql_delete = "DELETE FROM planning WHERE id_job = ? AND operation = ? AND machine = ? AND date_due = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("ssss", $id_job, $operation, $machine, $date_due);

    if ($stmt_delete->execute()) {
        // 🔹 เพิ่มประวัติการลบ (บันทึก JSON ของข้อมูลที่ถูกลบ)
        $deleted_data = json_encode([
            "work_order" => $job_info['work_order'],
            "item_no" => $job_info['item_no'],
            "operation" => $operation,
            "machine" => $machine,
            "op_color" => $job_info['op_color'],
            "op_side" => $job_info['op_side'],
            "date_due" => $date_due
        ], JSON_UNESCAPED_UNICODE);

        $action = "ลบงาน: Work Order {$job_info['work_order']}, Item No: {$job_info['item_no']}";
        $sql_log = "INSERT INTO history (username, action, details, date_time) VALUES (?, ?, ?, NOW())";
        $stmt_log = $conn->prepare($sql_log);
        $stmt_log->bind_param("sss", $username, $action, $deleted_data);
        $stmt_log->execute();
        $stmt_log->close();

        echo json_encode(array("statusCode" => 200, "message" => "Job row deleted successfully."), JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(array("statusCode" => 500, "message" => "Error deleting job row."), JSON_UNESCAPED_UNICODE);
    }
    $stmt_delete->close();
} else {
    echo json_encode(array("statusCode" => 400, "message" => "Invalid request. Missing parameters."), JSON_UNESCAPED_UNICODE);
}

require '../update/terminate.php'; // ปิดการเชื่อมต่อฐานข้อมูล
?>
