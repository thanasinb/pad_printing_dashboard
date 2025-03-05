<?php
require 'update/establish.php';

if (isset($_POST['id_staff'])) {
    $id_staff = $_POST['id_staff'];

    $sql = "SELECT name_first, name_last, id_role_group FROM staff WHERE id_staff = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id_staff);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (in_array($row['id_role_group'], [2, 3])) {
            echo json_encode([
                "statusCode" => 200,
                "name_first" => $row['name_first'],
                "name_last" => $row['name_last'],
                "role_group" => $row['id_role_group']
            ]);
        } else {
            echo json_encode(["statusCode" => 403, "message" => "Access denied"]);
        }
    } else {
        echo json_encode(["statusCode" => 404, "message" => "Staff not found"]);
    }

    $stmt->close();
    $conn->close();
}
?>