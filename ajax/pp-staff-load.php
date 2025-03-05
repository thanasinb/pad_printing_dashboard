<?php
require '../update/establish.php';

if (isset($_GET['id_staff'])) {
    $id_staff = $conn->real_escape_string($_GET['id_staff']);

    $sql = "SELECT id_staff, id_rfid, prefix, name_first, name_last, site, id_role, id_shif 
            FROM staff WHERE id_staff = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id_staff);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            "statusCode" => 200,
            "id_staff" => $row['id_staff'],
            "id_rfid" => $row['id_rfid'],
            "prefix" => $row['prefix'],
            "name_first" => $row['name_first'],
            "name_last" => $row['name_last'],
            "site" => $row['site'],
            "id_role" => $row['id_role'],
            "id_shif" => $row['id_shif']
        ]);
    } else {
        echo json_encode(["statusCode" => 404, "message" => "Staff not found"]);
    }

    $stmt->close();
} else {
    echo json_encode(["statusCode" => 400, "message" => "Invalid request"]);
}

require '../update/terminate.php';
?>