<?php
function get_break_by_id($conn, $table_break, $id_break)
{
    $sql = "SELECT break_code, break_start AS time_break FROM " . $table_break . " WHERE id_break=" . $id_break;
    $result = $conn->query($sql);
    $data_break = $result->fetch_assoc();

    return $data_break;
}