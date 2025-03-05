<?php
require 'update/establish.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_qr'])) {
    $id_qr = $_POST['id_qr'];

    // ใช้ Prepared Statement เพื่อความปลอดภัย
    $stmt = $conn->prepare("DELETE FROM qrcodes WHERE id_qr = ?");
    $stmt->bind_param("s", $id_qr);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
}
$conn->close();
?>
