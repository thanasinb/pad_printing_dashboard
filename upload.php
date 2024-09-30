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

    // ตั้งชื่อไฟล์
    $fileName = uniqid() . '.' . $image_type;
    $filePath = "uploads/" . $fileName;

    // บันทึกไฟล์ลงในโฟลเดอร์ uploads/
    if (file_put_contents($filePath, $image_base64)) {
        // อัปเดตชื่อไฟล์ในฐานข้อมูล
        $username = $_SESSION['username'];
        $sql = "UPDATE staff SET profile_image = ? WHERE id_staff = (SELECT id_staff FROM login WHERE username = ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $fileName, $username);

        if ($stmt->execute()) {
            // ส่งเส้นทางของภาพที่อัปโหลดสำเร็จ
            echo "/projects/mjrqr/uploads/" . $fileName;
        } else {
            echo "ERROR: มีข้อผิดพลาดในการอัปเดตโปรไฟล์: " . $conn->error;
        }
    } else {
        echo "ERROR: มีข้อผิดพลาดในการบันทึกไฟล์.";
    }
}
?>