<?php
require 'update/establish.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["profileImage"]) && isset($_POST["id_staff"])) {
    $staffId = $_POST["id_staff"];
    $uploadDir = "uploads/profile/";
    $fileName = uniqid() . "_" . basename($_FILES["profileImage"]["name"]);
    $targetFilePath = $uploadDir . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // ตรวจสอบประเภทไฟล์
    $allowTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($fileType, $allowTypes)) {
        echo "Error: Invalid file type.";
        exit();
    }

    // ตรวจสอบขนาดไฟล์ (5MB)
    if ($_FILES["profileImage"]["size"] > 5242880) {
        echo "Error: Image file size must be less than 5MB.";
        exit();
    }

    // อัปโหลดไฟล์
    if (move_uploaded_file($_FILES["profileImage"]["tmp_name"], $targetFilePath)) {
        // อัปเดตรูปโปรไฟล์ในฐานข้อมูล
        $sql_update = "UPDATE staff SET staff_img = ? WHERE id_staff = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ss", $fileName, $staffId);

        if ($stmt_update->execute()) {
            echo "Profile picture updated successfully.";
        } else {
            echo "Error: Unable to update profile picture.";
        }
        $stmt_update->close();
    } else {
        echo "Error: Failed to upload image.";
    }
} else {
    echo "Error: Invalid request.";
}

$conn->close();
?>
