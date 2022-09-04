<?php

require 'lib_get_info_from_activity_type.php';

function get_active_activity_by_id_and_machine($conn, $id_activity, $activity_type, $id_staff, $id_mc)
{

    list($table, $str_activity, $str_status) = get_info_from_activity_type($activity_type);

    $sql = "SELECT * FROM " . $table . " WHERE " .
        $str_activity . "=" . $id_activity . " AND 
        id_staff = " . $id_staff . " AND 
        id_machine = '" . $id_mc . "' AND " .
        $str_status . "=1";
    $result = $conn->query($sql);
    $data = $result->fetch_assoc();
    return $data;
}