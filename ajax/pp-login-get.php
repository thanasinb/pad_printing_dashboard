<?php
require '../update/establish.php';

//SELECT staff.id_staff, staff.id_rfid, prefix.prefix, staff.name_first, staff.name_last, staff.site, role.role, staff.id_shif FROM staff INNER JOIN role ON staff.id_role=role.id_role INNER JOIN prefix ON staff.prefix=prefix.id_prefix WHERE id_staff='0009'

if(isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // คิวรี่ฐานข้อมูลเพื่อตรวจสอบการเข้าสู่ระบบ
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    // ตรวจสอบผลลัพธ์ของคิวรี่
    if($result->num_rows > 0) {
        // ผู้ใช้ล็อกอินสำเร็จ
        echo "เข้าสู่ระบบสำเร็จ!";
    } else {
        // ไม่พบผู้ใช้หรือรหัสผ่านไม่ถูกต้อง
        echo "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง!";
    }
}

require '../update/terminate.php';

//echo json_encode(array("id_staff"=>$_POST["id_staff"]));

?>

