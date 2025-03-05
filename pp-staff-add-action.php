<?php
// เปิดการแสดงข้อผิดพลาดสำหรับการดีบัก
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'update/establish.php';
session_start(); // เริ่ม session เพื่อใช้งาน username

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// รับข้อมูลจากฟอร์มและตรวจสอบค่าที่ได้รับ
$required_fields = ['id_staff', 'id_rfid', 'prefix', 'name_first', 'name_last', 'id_role_group', 'id_role', 'id_shif'];
$data = [];

foreach ($required_fields as $field) {
    if (isset($_POST[$field]) && !empty(trim($_POST[$field]))) {
        $data[$field] = trim($_POST[$field]);
    } else {
        echo "<script>alert('Error: Please fill in all required fields.'); window.location.href='pp-staff-add.php?message=empty_fields';</script>";
        exit();
    }
}

// ตรวจสอบข้อมูลซ้ำในฐานข้อมูล
$sql_check = "SELECT id_staff FROM staff WHERE id_staff = ? OR id_rfid = ?";
$stmt_check = $conn->prepare($sql_check);

if (!$stmt_check) {
    die("Prepare failed: " . $conn->error);
}

$stmt_check->bind_param("ss", $data['id_staff'], $data['id_rfid']);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    echo "<script>alert('Error: Staff ID or RFID already exists. Please use a different one.'); window.location.href='pp-staff-add.php?message=duplicate';</script>";
    $stmt_check->close();
    $conn->close();
    exit();
}

$stmt_check->close();

// ดึงชื่อ Role และ Role Group จากฐานข้อมูล
$sql_role = "SELECT role FROM role WHERE id_role = ?";
$stmt_role = $conn->prepare($sql_role);
$stmt_role->bind_param("s", $data['id_role']);
$stmt_role->execute();
$stmt_role->bind_result($role_name);
$stmt_role->fetch();
$stmt_role->close();

$sql_role_group = "SELECT role_group_name FROM role_group WHERE id_role_group = ?";
$stmt_role_group = $conn->prepare($sql_role_group);
$stmt_role_group->bind_param("s", $data['id_role_group']);
$stmt_role_group->execute();
$stmt_role_group->bind_result($role_group_name);
$stmt_role_group->fetch();
$stmt_role_group->close();


// ** การอัปโหลดรูปภาพโปรไฟล์ **
$uploadDir = "uploads/profile/";
$profileImage = NULL;

if (!empty($_FILES['profileImage']['name'])) {
    $fileName = uniqid() . "_" . basename($_FILES["profileImage"]["name"]);
    $targetFilePath = $uploadDir . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // ตรวจสอบประเภทไฟล์ที่อนุญาต
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
    if (in_array($fileType, $allowTypes)) {
        // ตรวจสอบว่ามีโฟลเดอร์หรือไม่ ถ้าไม่มีให้สร้าง
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($_FILES["profileImage"]["tmp_name"], $targetFilePath)) {
            $profileImage = $fileName; // เก็บชื่อไฟล์สำหรับบันทึกฐานข้อมูล
        } else {
            echo "<script>alert('Error: Unable to upload profile image.'); window.location.href='pp-staff-add.php?message=upload_error';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Error: Only JPG, PNG, and GIF files are allowed.'); window.location.href='pp-staff-add.php?message=invalid_file';</script>";
        exit();
    }
}

// ** เพิ่มค่าลงไปในคำสั่ง SQL เดิม **
$sql_insert = "INSERT INTO staff (id_staff, id_rfid, prefix, name_first, name_last, id_role, id_role_group, id_shif, staff_img) 
               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt_insert = $conn->prepare($sql_insert);

if (!$stmt_insert) {
    die("Prepare statement failed: " . $conn->error);
}

// ** ปรับให้ bind_param มี 9 ค่าตามที่เพิ่มมา **
$stmt_insert->bind_param(
    "sssssssss",
    $data['id_staff'],
    $data['id_rfid'],
    $data['prefix'],
    $data['name_first'],
    $data['name_last'],
    $data['id_role'],
    $data['id_role_group'],
    $data['id_shif'],
    $profileImage // เพิ่มรูปโปรไฟล์ลงฐานข้อมูล
);

// ทำการเพิ่มข้อมูลเข้าไปในฐานข้อมูล
if ($stmt_insert->execute()) {
    // บันทึกข้อมูลลง history
    $username = $_SESSION['username']; // ดึงชื่อผู้ใช้จาก session
    $staff_name = ($data['prefix'] == '1' ? 'นาย' : ($data['prefix'] == '2' ? 'นาง' : 'นางสาว')) . " " . $data['name_first'] . " " . $data['name_last'];
    $action = "สร้างพนักงาน: $staff_name";

    // เก็บรายละเอียดที่ถูกเพิ่มเข้าไป
    $details = json_encode([
        "Staff ID: " . $data['id_staff'],
        "RFID: " . $data['id_rfid'],
        "Prefix: " . ($data['prefix'] == '1' ? 'นาย' : ($data['prefix'] == '2' ? 'นาง' : 'นางสาว')),
        "First name: " . $data['name_first'],
        "Last name: " . $data['name_last'],
        "Role Group: " . $role_group_name,
        "Role: " . $role_name,
        "Shift: " . $data['id_shif'],
        "Profile Image: " . ($profileImage ? $profileImage : "No Image") // เพิ่มรูปโปรไฟล์ใน history
    ], JSON_UNESCAPED_UNICODE);
    $final_action = $action;

    // บันทึกลง history
    $sql_log = "INSERT INTO history (username, action, details, date_time) VALUES (?, ?, ?, NOW())";
    $stmt_log = $conn->prepare($sql_log);
    $stmt_log->bind_param("sss", $username, $final_action, $details);
    $stmt_log->execute();
    $stmt_log->close();

    echo "<script>alert('Success: Staff data has been added successfully.'); window.location.href='pp-staff-add.php?message=success';</script>";
} else {
    echo "<script>alert('Error: Unable to add staff data. Please try again.'); window.location.href='pp-staff-add.php?message=error';</script>";
}

$stmt_insert->close();
$conn->close();

?>