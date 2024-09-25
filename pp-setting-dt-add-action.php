<?php
session_start(); // เริ่ม session ทุกครั้งที่ใช้งาน session variables

$error_code = 0;

// เชื่อมต่อกับฐานข้อมูล
require 'update/establish.php';

// ตรวจสอบว่ามี id_code_downtime นี้ในฐานข้อมูลหรือไม่
$sql_check = "SELECT id_code_downtime FROM code_downtime WHERE id_code_downtime=?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $_POST['box_code']);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    // ถ้ามี id_code_downtime นี้อยู่แล้วในฐานข้อมูล
    $error_code = 603; // Error code 603 หมายถึง id_code_downtime นี้มีอยู่แล้ว
} else {
    // ถ้ายังไม่มี id_code_downtime นี้ในฐานข้อมูล
    $sql_insert = "INSERT INTO code_downtime (id_code_downtime, code_downtime, des_downtime, des_downtime_thai, enable, date_setting) VALUES (?, ?, ?, ?, 1, CURRENT_TIMESTAMP)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("ssss", $_POST['box_code'], $_POST['downtime_code'], $_POST['description_eng'], $_POST['description_tha']);

    if ($stmt_insert->execute()) {
        // บันทึกประวัติการเพิ่ม downtime ลงในฐานข้อมูล
        $username = $_SESSION['username'];
        $action = "เพิ่ม Downtime: {$_POST['downtime_code']}";
        $sql_log_action = "INSERT INTO history (username, action, date_time) VALUES (?, ?, NOW())";
        $stmt_log_action = $conn->prepare($sql_log_action);
        $stmt_log_action->bind_param("ss", $username, $action);
        $stmt_log_action->execute();

        $stmt_log_action->close();
        $stmt_insert->close();
    } else {
        // หากเกิดข้อผิดพลาดในการ execute คำสั่ง SQL
        $error_code = $stmt_insert->errno; // ระบุ error code ในกรณีที่เกิดข้อผิดพลาด
    }
}

// ปิดการเชื่อมต่อกับฐานข้อมูล
require 'update/terminate.php';

// Redirect ไปยังหน้า pp-setting-dt-add.php พร้อมส่ง error code กลับไปด้วย
header("Location: ./pp-setting-dt-add.php?error_code=" . $error_code);
die();
?>
