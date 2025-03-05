<?php
session_start();
require 'update/establish.php';



if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['croppedImage'])) {
    // รับภาพที่ถูกครอบ (base64)
    $croppedImage = $_POST['croppedImage'];

    // Decode base64 image
    $image_parts = explode(";base64,", $croppedImage);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
    $image_base64 = base64_decode($image_parts[1]);

    // ตรวจสอบขนาดไฟล์ (ไม่เกิน 5MB)
    if (strlen($image_base64) > 5 * 1024 * 1024) {
        echo "ERROR: File size exceeds 5MB.";
        exit();
    }

    // ตรวจสอบประเภทไฟล์ (อนุญาตเฉพาะ jpg และ png)
    $allowed_types = ['jpeg', 'png', 'jpg'];
    if (!in_array($image_type, $allowed_types)) {
        echo "ERROR: Invalid file type.";
        exit();
    }

    // ตั้งชื่อไฟล์ใหม่
    $fileName = uniqid() . '.' . $image_type;
    $filePath = "uploads/" . $fileName;

    // เชื่อมต่อกับฐานข้อมูล
    $username = $_SESSION['username'];

    // ดึงภาพโปรไฟล์เก่าจากฐานข้อมูล
    $sql = "SELECT profile_image FROM staff WHERE id_staff = (SELECT id_staff FROM login WHERE username = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // ลบไฟล์ภาพเก่า (ถ้ามี)
    if ($row && !empty($row['profile_image'])) {
        $oldFilePath = "uploads/" . $row['profile_image'];
        if (file_exists($oldFilePath)) {
            unlink($oldFilePath); // ลบไฟล์เก่า
        }
    }

    // บันทึกไฟล์ใหม่
    if (file_put_contents($filePath, $image_base64)) {
        // อัปเดตชื่อไฟล์ใหม่ในฐานข้อมูล
        $sql = "UPDATE staff SET profile_image = ? WHERE id_staff = (SELECT id_staff FROM login WHERE username = ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $fileName, $username);

        if ($stmt->execute()) {
            // ส่ง URL ของภาพที่อัปโหลดสำเร็จกลับไป
            echo "/projects/mjrqr/uploads/" . $fileName;
        } else {
            echo "ERROR: Failed to update profile image.";
        }
    } else {
        echo "ERROR: Failed to save file.";
    }
}
?>