<?php
function get_active_activity_by_id_and_machine($conn, $table, $status, $id_staff, $id_mc)
{
    $sql = "SELECT * FROM " . $table . " WHERE id_staff = " . $id_staff . " AND id_machine = '" . $id_mc . "' AND " . $status. "=1";
    $result = $conn->query($sql);
    $data = $result->fetch_assoc();
    return $data;
}