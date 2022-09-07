<?php
function get_active_activity_by_machine($conn, $table, $str_status, $id_mc)
{
    $sql = "SELECT * FROM " . $table . " WHERE " .
        "id_machine = '" . $id_mc . "' AND " .
        $str_status . "=1";

    $result = $conn->query($sql);
    $data = $result->fetch_assoc();
    return $data;
}