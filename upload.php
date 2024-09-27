<?php
require 'update/establish.php';  // เชื่อมต่อกับฐานข้อมูล
session_start();

if (isset($_FILES['imageFile']) && isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // ดึง id_staff จากตาราง staff ตาม username
    $staff_id_query = $conn->prepare("SELECT id_staff FROM staff WHERE username = ?");
    $staff_id_query->bind_param("s", $username);
    $staff_id_query->execute();
    $result = $staff_id_query->get_result();

    if ($result->num_rows > 0) {
        $staff = $result->fetch_assoc();
        $staff_id = $staff['id_staff'];

        // กำหนดโฟลเดอร์และชื่อไฟล์ที่จะบันทึก
        $target_dir = "uploads/";
        $imageFileType = strtolower(pathinfo(basename($_FILES["imageFile"]["name"]), PATHINFO_EXTENSION));
        $target_file = $target_dir . uniqid() . "." . $imageFileType;

        // ย้ายไฟล์ไปยังโฟลเดอร์ที่กำหนด
        if (move_uploaded_file($_FILES["imageFile"]["tmp_name"], $target_file)) {
            // เพิ่มหรืออัพเดทข้อมูลรูปโปรไฟล์ในฐานข้อมูล
            $query = $conn->prepare("INSERT INTO staff_profile_images (staff_id, image_path) VALUES (?, ?) 
                                      ON DUPLICATE KEY UPDATE image_path = VALUES(image_path)");
            $query->bind_param("is", $staff_id, $target_file);

            if ($query->execute()) {
                echo $target_file;  // ส่งคืนเส้นทางรูปภาพ
            } else {
                echo "ERROR: ไม่สามารถบันทึกรูปโปรไฟล์ในฐานข้อมูลได้";
            }
        } else {
            echo "ERROR: อัพโหลดไฟล์ไม่สำเร็จ";
        }
    } else {
        echo "ERROR: ไม่พบผู้ใช้ในฐานข้อมูล";
    }
}
?>