<?php
require 'update/establish.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $qty_per_pulse2 = floatval($_POST['qty_per_pulse2']);

    $sql = "UPDATE special_tray_data
    SET qty_per_pulse2 = ?,
        is_display_camforeman = 0,
        is_visible = 1  -- ✅ เพิ่มเงื่อนไขนี้
    WHERE id = ?";


    $stmt = $conn->prepare($sql);
    $stmt->bind_param("di", $qty_per_pulse2, $id);

    if ($stmt->execute()) {
        // ส่งผลลัพธ์กลับในรูปแบบ JSON
        echo json_encode(["status" => "success", "id" => $id]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }

    $stmt->close();
    $conn->close();
}
?>