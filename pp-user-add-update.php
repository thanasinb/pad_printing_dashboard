<?php
require 'update/establish.php';

// ตรวจสอบว่ามีการส่งข้อมูลแบบ POST มาหรือไม่ เพื่ออัปเดตข้อมูล
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idStaff = $_POST['new_id_staff'];
    $username = $_POST['new_username'];
    $password = $_POST['new_password'];

    // อัปเดตข้อมูลผู้ใช้ในตาราง login
    $sql = "UPDATE login SET 
                    username='$username', 
                    password='$password' 
                    WHERE id_staff='$idStaff'";
    $result = $conn->query($sql);

    if ($result === TRUE) {
        echo json_encode(array("statusCode" => 200, "message" => "User updated successfully."), JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(array("statusCode" => 500, "message" => "Error updating user: " . $conn->error), JSON_UNESCAPED_UNICODE);
    }
}

require 'update/terminate.php';
?>
