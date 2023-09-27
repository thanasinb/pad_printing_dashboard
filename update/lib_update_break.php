<?php
function update_break($conn, $table, $table_break, $str_activity, $str_status, $id_activity, $id_break, $time_current, $break_duration, $str_break, $total_break){

    $sql = "UPDATE " . $table . " SET " . $str_status . "=1, " . $str_break . "='" . $total_break . "', " .
           "time_previous='" . $time_current . "' WHERE " . $str_activity . "=" . $id_activity;
    $result = $conn->query($sql);

    $sql = "UPDATE " . $table_break . " SET " .
        "break_stop='" . $time_current . "', " .
        "break_duration='" . $break_duration . "' " .
        "WHERE id_break=" . $id_break;
    $result = $conn->query($sql);

}
