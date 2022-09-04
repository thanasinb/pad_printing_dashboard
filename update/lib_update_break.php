<?php
function update_break($conn, $table, $table_break, $id_activity, $id_break, $time_current, $break_duration, $str_break, $total_break){

    $sql = "UPDATE " . $table . " SET status_work=1, " . $str_break . "='" . $total_break . "' WHERE id_activity=" . $id_activity;
    $result = $conn->query($sql);

    $sql = "UPDATE " . $table_break . " SET " .
        "break_stop='" . $time_current . "', " .
        "break_duration='" . $break_duration . "' " .
        "WHERE id_break=" . $id_break;
    $result = $conn->query($sql);

}
