<?php
function get_staff_by_id($conn, $id_staff)
{
    $sql = "SELECT id_rfid, id_staff, name_first, name_last, id_role AS role, id_shif AS team FROM staff WHERE id_staff='" . $id_staff . "'";
    echo $sql;
    $result = $conn->query($sql);
    $data = $result->fetch_assoc();
    echo "<br>" . $data['role'] . "<br>";
    $data['role']=intval($data['role']);
    return $data;
}