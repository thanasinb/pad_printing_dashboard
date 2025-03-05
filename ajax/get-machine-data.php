<?php
require '../update/establish.php';

if (isset($_GET['id_mc'])) {
    $id_mc = $_GET['id_mc'];

    // ดึงข้อมูลจากตาราง machine ตาม id_mc
    $sql = "SELECT id_mc, id_mc_type, mc_des, id_cam FROM machine WHERE id_mc = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id_mc);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode(array(
            "statusCode" => 200,
            "data" => $data
        ), JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(array("statusCode" => 404, "message" => "Machine not found."));
    }

    $stmt->close();
} else {
    echo json_encode(array("statusCode" => 400, "message" => "Invalid request."));
}

require '../update/terminate.php';
?>
