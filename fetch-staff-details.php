<?php
require 'update/establish.php'; // เชื่อมต่อฐานข้อมูล

if (isset($_POST['id_staff'])) {
    $id_staff = trim($_POST['id_staff']);
    $stmt = $conn->prepare("SELECT name_first, name_last FROM staff WHERE id_staff = ?");
    $stmt->bind_param("s", $id_staff);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(["statusCode" => 200, "name_first" => $row['name_first'], "name_last" => $row['name_last']]);
    } else {
        echo json_encode(["statusCode" => 404, "message" => "No staff found"]);
    }

    $stmt->close();
    $conn->close();
}
?>