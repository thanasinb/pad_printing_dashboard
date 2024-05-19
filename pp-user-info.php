<?php
// เชื่อมต่อกับฐานข้อมูล
require 'update/establish.php';

// ตรวจสอบว่ามีการรับค่า id_staff ผ่านแบบ GET หรือไม่
if(isset($_GET['id_staff']) && !empty($_GET['id_staff'])){
    // นำค่า id_staff ที่รับเข้ามาใช้ในการโหลดข้อมูล
    $idStaff = $_GET['id_staff'];

    // คำสั่ง SQL สำหรับดึงข้อมูลของ staff จาก id_staff
    $sql = "SELECT * FROM staff WHERE id_staff = '$idStaff'";

    // ทำการค้นหาข้อมูล
    $result = $conn->query($sql);

    // ตรวจสอบว่ามีข้อมูลหรือไม่
    if ($result->num_rows > 0) {
        // ดึงข้อมูลออกมาเป็น array
        $row = $result->fetch_assoc();

        // สร้าง associative array เพื่อใช้ในการส่งข้อมูลกลับ
        $response['statusCode'] = 200;
        $response['id_staff'] = $row['id_staff'];
        $response['name_first'] = $row['name_first'];
        $response['name_last'] = $row['name_last'];
        // สามารถเพิ่มข้อมูลเพิ่มเติมตามที่ต้องการ

        // ส่งข้อมูลกลับเป็น JSON
        echo json_encode($response);
    } else {
        // ไม่พบข้อมูล staff
        $response['statusCode'] = 404;
        $response['message'] = "ไม่พบข้อมูล staff ที่ระบุ";
        echo json_encode($response);
    }
} else {
    // ไม่ได้รับค่า id_staff หรือไม่ถูกต้อง
    $response['statusCode'] = 400;
    $response['message'] = "ไม่ได้รับข้อมูล id_staff";
    echo json_encode($response);
}

// ปิดการเชื่อมต่อกับฐานข้อมูล
$conn->close();
?>
