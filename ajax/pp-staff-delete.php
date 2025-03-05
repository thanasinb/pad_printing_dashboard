<?php
require '../update/establish.php';
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(array("statusCode" => 403, "message" => "Unauthorized access."));
    exit();
}

$username = $_SESSION['username'];

if (isset($_GET['id_staff'])) {
    $id_staff = $conn->real_escape_string($_GET['id_staff']);

    // ดึงข้อมูลพนักงานก่อนลบ เพื่อนำไปบันทึกในประวัติ
    $sql_select = "SELECT prefix, name_first, name_last FROM staff WHERE id_staff = ?";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param("s", $id_staff);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    $staff_info = $result->fetch_assoc();
    $stmt_select->close();

    if ($staff_info) {
        // ลบข้อมูลพนักงาน (ตั้งค่า active เป็น 0)
        $sql_update = "UPDATE staff SET active=0 WHERE id_staff=?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("s", $id_staff);

        if ($stmt_update->execute()) {
            // แปลงคำนำหน้าเป็นข้อความ
            $prefix_text = ($staff_info['prefix'] == 1 ? "นาย" : ($staff_info['prefix'] == 2 ? "นาง" : "นางสาว"));

            // เพิ่มประวัติการลบลงในฐานข้อมูล
            $action = "ลบพนักงาน: $prefix_text {$staff_info['name_first']} {$staff_info['name_last']}";
            $sql_log = "INSERT INTO history (username, action, date_time) VALUES (?, ?, NOW())";
            $stmt_log = $conn->prepare($sql_log);
            $stmt_log->bind_param("ss", $username, $action);
            $stmt_log->execute();
            $stmt_log->close();

            echo json_encode(array("statusCode" => 200, "message" => "Staff deleted successfully."), JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(array("statusCode" => 500, "message" => "Error deleting staff."), JSON_UNESCAPED_UNICODE);
        }
        $stmt_update->close();
    } else {
        echo json_encode(array("statusCode" => 404, "message" => "Staff not found."), JSON_UNESCAPED_UNICODE);
    }
} else {
    echo json_encode(array("statusCode" => 400, "message" => "Missing required parameters."), JSON_UNESCAPED_UNICODE);
}

require '../update/terminate.php';
?>