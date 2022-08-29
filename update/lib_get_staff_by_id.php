<?php
function get_staff_by_id($conn, $id_staff)
{
    $sql = "SELECT id_staff, name_first, name_last, id_role as role FROM staff WHERE id_staff='" . $id_staff . "'";
    $result = $conn->query($sql);
    $data = $result->fetch_assoc();
    $data["role"]=intval($data["role"]);
    return $data;
}