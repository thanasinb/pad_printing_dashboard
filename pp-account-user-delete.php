<?php
// เชื่อมต่อกับฐานข้อมูล
require 'update/establish.php';

// ตรวจสอบว่ามีการรับค่า id_staff ผ่านแบบ GET หรือไม่
if(isset($_GET['id_staff']) && !empty($_GET['id_staff'])){
    // นำค่า id_staff ที่รับเข้ามาใช้ในการลบ
    $idStaff = $_GET['id_staff'];

    // คำสั่ง SQL สำหรับลบผู้ใช้
    $sql = "DELETE FROM login WHERE id_staff = '$idStaff'";

    // ทำการลบผู้ใช้
    if ($conn->query($sql) === TRUE) {
        // ส่งคำตอบกลับให้กับ JavaScript เพื่อแจ้งผลการลบ
        $response['statusCode'] = 200;
        $response['message'] = "ลบผู้ใช้เรียบร้อยแล้ว";
        echo json_encode($response);
    } else {
        // ส่งคำตอบกลับให้กับ JavaScript เพื่อแจ้งผลการลบ
        $response['statusCode'] = 500;
        $response['message'] = "ไม่สามารถลบผู้ใช้ได้: " . $conn->error;
        echo json_encode($response);
    }
} else {
    // ส่งคำตอบกลับให้กับ JavaScript เพื่อแจ้งผลการลบ
    $response['statusCode'] = 400;
    $response['message'] = "ไม่ได้รับข้อมูล id_staff";
    echo json_encode($response);
}

// ปิดการเชื่อมต่อกับฐานข้อมูล
$conn->close();
?>
