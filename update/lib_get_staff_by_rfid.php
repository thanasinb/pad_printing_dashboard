<?php
function get_staff_by_rfid($conn, $id_rfid)
{
    $sql = "SELECT id_staff, name_first, name_last, id_role as role FROM staff WHERE id_rfid='" . $id_rfid . "'";
    $result = $conn->query($sql);
    $data = $result->fetch_assoc();
    return $data;
}