<?php
function get_staff_by_rfid($conn, $id_rfid)
{
    $sql = "SELECT 
        staff.id_staff, 
        staff.name_first, 
        staff.name_last, 
        role.role_group as role 
        FROM staff 
        LEFT JOIN role ON staff.id_role = role.id_role 
        WHERE staff.id_rfid='" . $id_rfid . "' AND staff.active=1";
    $result = $conn->query($sql);
    $data = $result->fetch_assoc();
    return $data;
}