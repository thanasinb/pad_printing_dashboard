<?php
require '../update/establish.php';
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(array("statusCode" => 403, "message" => "Unauthorized access."), JSON_UNESCAPED_UNICODE);
    exit();
}

// ✅ Debugging: ตรวจสอบค่าที่ได้รับจาก AJAX
error_log("Received POST Data: " . print_r($_POST, true));

if (isset($_POST['id_job']) && !empty($_POST['id_job'])) {
    $id_job = $conn->real_escape_string($_POST['id_job']);

    // 🔹 Debugging ตรวจสอบค่า ID ที่ได้รับ
    error_log("Fetching job data for ID: " . $id_job);

    // ดึงข้อมูลจากฐานข้อมูล
    $sql = "SELECT id_job, operation, machine, work_order, item_no, op_color, op_side, date_due FROM planning WHERE id_job = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id_job);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $job = $result->fetch_assoc();
        echo json_encode(array("statusCode" => 200, "data" => $job), JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(array("statusCode" => 404, "message" => "Job not found."), JSON_UNESCAPED_UNICODE);
    }
    $stmt->close();
} else {
    echo json_encode(array("statusCode" => 400, "message" => "Invalid request. Missing parameters."), JSON_UNESCAPED_UNICODE);
}

require '../update/terminate.php';
?>
