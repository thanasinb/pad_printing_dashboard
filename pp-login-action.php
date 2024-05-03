<?php
// เรียกใช้งานไฟล์เชื่อมต่อกับฐานข้อมูล
require 'update/establish.php';

// ตรวจสอบว่ามีการส่งข้อมูล username และ password มาหรือไม่
if(isset($_POST['username']) && isset($_POST['password'])) {
    // รับค่า username และ password จากฟอร์ม
    $username = $_POST['username'];
    $password = $_POST['password'];

    // คำสั่ง SQL สำหรับเลือกข้อมูลผู้ใช้จากฐานข้อมูล
    $sql = "SELECT * FROM login WHERE username='$username' AND password='$password'";

    // ทำการคิวรีฐานข้อมูล
    $result = $conn->query($sql);

    // ตรวจสอบว่าคิวรีสำเร็จหรือไม่
    if($result->num_rows > 0) {
        // หากพบข้อมูลผู้ใช้ในฐานข้อมูล
        // กำหนดให้ในการเชื่อมต่อออกจากฐานข้อมูลต้องปิด
        require 'update/terminate.php';

        // เปลี่ยนเส้นทางไปยังหน้าหลักหลังจากเข้าสู่ระบบสำเร็จ
        header("Location: pp-homepage.php");
        exit();
    } else {
        // หากไม่พบข้อมูลผู้ใช้ในฐานข้อมูล
        // กำหนดให้ในการเชื่อมต่อออกจากฐานข้อมูลต้องปิด
        require 'update/terminate.php';

        // กลับไปยังหน้าล็อกอินเพื่อลองเข้าสู่ระบบอีกครั้ง
        header("Location: pp-login.php");
        exit();
    }
}
?>
