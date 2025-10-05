<?php
function get_break_activity_by_machine($conn, $table, $str_status, $id_mc)
{
    $sql = "SELECT *, CURRENT_TIMESTAMP() AS time_current FROM " . $table . " WHERE " .
        "id_machine = '" . $id_mc . "' AND " .
        $str_status . "<3";

    $result = $conn->query($sql);
    $data = $result->fetch_assoc();
    return $data;
}