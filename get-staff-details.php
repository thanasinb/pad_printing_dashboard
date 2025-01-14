<?php
require 'update/establish.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_staff = trim($_POST['id_staff']);

    if (!empty($id_staff)) {
        $stmt = $conn->prepare("SELECT name_first, name_last FROM staff WHERE id_staff = ?");
        $stmt->bind_param("s", $id_staff);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo json_encode([
                "statusCode" => 200,
                "name_first" => $row['name_first'],
                "name_last" => $row['name_last']
            ]);
        } else {
            echo json_encode(["statusCode" => 404, "message" => "Staff not found"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["statusCode" => 400, "message" => "Invalid Staff ID"]);
    }

    $conn->close();
}
?>