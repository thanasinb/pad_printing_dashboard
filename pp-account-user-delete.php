<?php
// เชื่อมต่อกับฐานข้อมูล
require 'update/establish.php';
session_start(); // ใช้สำหรับดึงผู้ที่ทำการลบ

// ตรวจสอบการรับค่า id_staff
if (isset($_GET['id_staff']) && !empty($_GET['id_staff'])) {
    $idStaff = $_GET['id_staff'];

    // ดึง username, ชื่อ และ นามสกุล จาก id_staff
    $stmtSelect = $conn->prepare("
        SELECT login.username, staff.name_first, staff.name_last 
        FROM login 
        LEFT JOIN staff ON login.id_staff = staff.id_staff 
        WHERE login.id_staff = ?
    ");
    $stmtSelect->bind_param("s", $idStaff);
    $stmtSelect->execute();
    $result = $stmtSelect->get_result();

    if ($row = $result->fetch_assoc()) {
        $usernameToDelete = $row['username'];
        $fullName = $row['name_first'] . ' ' . $row['name_last'];

        // ลบข้อมูลผู้ใช้จากตาราง login
        $stmtDelete = $conn->prepare("DELETE FROM login WHERE id_staff = ?");
        $stmtDelete->bind_param("s", $idStaff);

        if ($stmtDelete->execute()) {
            // บันทึกประวัติการลบใน history เป็น "ลบผู้ใช้: username ของ ชื่อ นามสกุล"
            $action = "ลบผู้ใช้: " . $usernameToDelete . " ของ " . $fullName;
            $adminUsername = $_SESSION['username'] ?? 'system'; // ผู้ที่ทำการลบ
            $stmtHistory = $conn->prepare("INSERT INTO history (username, action, date_time) VALUES (?, ?, CURRENT_TIMESTAMP)");
            $stmtHistory->bind_param("ss", $adminUsername, $action);
            $stmtHistory->execute();

            // ส่งสถานะ JSON กลับไป
            echo json_encode(["statusCode" => 200, "message" => "ลบผู้ใช้เรียบร้อยแล้ว"]);
        } else {
            echo json_encode(["statusCode" => 500, "message" => "ไม่สามารถลบผู้ใช้ได้: " . $conn->error]);
        }
    } else {
        echo json_encode(["statusCode" => 404, "message" => "ไม่พบผู้ใช้ที่ต้องการลบ"]);
    }
} else {
    echo json_encode(["statusCode" => 400, "message" => "ไม่ได้รับข้อมูล id_staff"]);
}

// ปิดการเชื่อมต่อ
$conn->close();
?>