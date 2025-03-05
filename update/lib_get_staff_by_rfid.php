<?php
function get_staff_by_rfid($conn, $id_rfid)
{
    $sql = "SELECT id_staff, name_first, name_last, id_role as role 
            FROM staff 
            WHERE id_rfid = ? AND active = 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id_rfid);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();

    return $data;
}
?>