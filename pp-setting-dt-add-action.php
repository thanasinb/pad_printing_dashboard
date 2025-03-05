<?php
session_start(); // เริ่ม session เพื่อใช้งาน username

// เปิดการแสดงข้อผิดพลาดสำหรับการ debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'update/establish.php';

// ตรวจสอบว่ามีค่า $_POST ครบถ้วน
$required_fields = ['box_code', 'downtime_code', 'description_eng', 'description_tha'];
$data = [];

foreach ($required_fields as $field) {
    if (isset($_POST[$field]) && !empty(trim($_POST[$field]))) {
        $data[$field] = trim($_POST[$field]);
    } else {
        echo "<script>alert('Error: Please fill in all required fields.'); window.location.href='pp-setting-dt-add.php?message=empty_fields';</script>";
        exit();
    }
}

// ตรวจสอบว่ามี Downtime Code นี้อยู่แล้วหรือไม่
$sql_check = "SELECT id_code_downtime FROM code_downtime WHERE id_code_downtime=?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $data['box_code']);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    echo "<script>alert('Error: Downtime Code already exists. Please use a different one.'); window.location.href='pp-setting-dt-add.php?message=duplicate';</script>";
    $stmt_check->close();
    $conn->close();
    exit();
}

$stmt_check->close();

// เพิ่มข้อมูลลงในฐานข้อมูล
$sql_insert = "INSERT INTO code_downtime (id_code_downtime, code_downtime, des_downtime, des_downtime_thai, enable, date_setting) 
               VALUES (?, ?, ?, ?, 1, CURRENT_TIMESTAMP)";
$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bind_param(
    "ssss",
    $data['box_code'],
    $data['downtime_code'],
    $data['description_eng'],
    $data['description_tha']
);

if ($stmt_insert->execute()) {
    // บันทึกลง history
    $username = $_SESSION['username'];
    $action = "เพิ่ม Downtime Code: " . $data['downtime_code'];

    // รายละเอียดที่เพิ่มเข้าไป
    $details = json_encode([
        "Box Code: " . $data['box_code'],
        "Downtime Code: " . $data['downtime_code'],
        "Description Eng: " . $data['description_eng'],
        "Description Thai: " . $data['description_tha']
    ], JSON_UNESCAPED_UNICODE);
    $final_action = $action ;

    $sql_log = "INSERT INTO history (username, action, details, date_time) VALUES (?, ?, ?, NOW())";
    $stmt_log = $conn->prepare($sql_log);
    $stmt_log->bind_param("sss", $username, $final_action, $details);
    $stmt_log->execute();
    $stmt_log->close();

    echo "<script>alert('Success: Downtime Code has been added successfully.'); window.location.href='pp-setting-dt-add.php?message=success';</script>";
} else {
    echo "<script>alert('Error: Unable to add Downtime Code. Please try again.'); window.location.href='pp-setting-dt-add.php?message=error';</script>";
}

$stmt_insert->close();
$conn->close();
?>