<?php
// upload.php

// ตรวจสอบว่ามีไฟล์ถูกอัปโหลดหรือไม่
if (isset($_FILES['imageFile'])) {
    $file = $_FILES['imageFile'];

    // เช็คว่าไม่มีข้อผิดพลาดในการอัปโหลด
    if ($file['error'] === UPLOAD_ERR_OK) {
        // สร้างชื่อไฟล์ใหม่
        $filename = uniqid() . '_' . $file['name'];
        $filepath = '/path/to/upload/directory/' . $filename; // เปลี่ยนเป็นที่อยู่ของไดเรกทอรีที่ต้องการบันทึก

        // ย้ายไฟล์ไปยังที่อยู่ใหม่
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            // ส่ง URL ของรูปภาพกลับไปยัง JavaScript เพื่อให้ใช้ในการอัปเดตรูปภาพใน HTML
            $response = ['imageUrl' => '/path/to/upload/directory/' . $filename]; // เปลี่ยนเป็น URL ของไฟล์ที่อัปโหลด
            echo json_encode($response);
            exit;
        } else {
            // กรณีที่มีข้อผิดพลาดในการย้ายไฟล์
            http_response_code(500);
            echo json_encode(['error' => 'Failed to move file']);
            exit;
        }
    } else {
        // กรณีที่มีข้อผิดพลาดในการอัปโหลด
        http_response_code(500);
        echo json_encode(['error' => 'Upload failed']);
        exit;
    }
}
?>
