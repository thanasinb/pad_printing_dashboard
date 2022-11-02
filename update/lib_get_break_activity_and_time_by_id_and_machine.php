<?php
function get_break_activity_and_time_by_id_and_machine($conn, $table, $str_activity, $str_status, $id_activity, $id_mc)
{
    $sql = "SELECT id_break, total_food, total_toilet, CURRENT_TIMESTAMP() AS time_current 
        FROM " . $table . " 
        WHERE " . $str_status . "=2 
         AND " . $str_activity . "='" . $id_activity . "'" .
        "AND id_machine='" . $id_mc . "'";
    $result = $conn->query($sql);
    $data = $result->fetch_assoc();
    return $data;
}