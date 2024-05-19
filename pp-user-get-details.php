<?php
require 'update/establish.php';

if (isset($_GET['id_staff'])) {
    $idStaff = $_GET['id_staff'];

    $stmt = $conn->prepare("SELECT login.id_staff, login.username, login.password, staff.name_first, staff.name_last 
                            FROM login 
                            LEFT JOIN staff ON login.id_staff = staff.id_staff 
                            WHERE login.id_staff = ?");
    $stmt->bind_param("s", $idStaff);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();

    if ($data) {
        echo json_encode(array("statusCode" => 200, "data" => $data), JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(array("statusCode" => 404, "message" => "ไม่พบข้อมูลผู้ใช้"), JSON_UNESCAPED_UNICODE);
    }
}

require 'update/terminate.php';
?>
