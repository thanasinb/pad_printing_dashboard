<?php
function add_break($conn, $table, $table_break, $id_activity, $id_staff, $id_mc, $break_code){
    $sql = "INSERT INTO " . $table_break . " (id_activity, id_staff, break_code, break_start) VALUES (";
    $sql = $sql . $id_activity . ",";
    $sql = $sql . "'" . $id_staff . "',";
    $sql = $sql . $break_code . ",";
    $sql = $sql . "CURRENT_TIMESTAMP())";
    $result = $conn->query($sql);

    $sql = 'SELECT LAST_INSERT_ID() as id_break';
    $result = $conn->query($sql);
    $data_break = $result->fetch_assoc();

    $sql = "UPDATE " . $table . " SET ";
    $sql = $sql . "id_break=" . $data_break['id_break'] . ",";
    $sql = $sql . "status_work=2";
    $sql = $sql . " WHERE id_machine='" . $id_mc . "'";;
    $sql = $sql . " AND id_activity=" . $id_activity;
    $result = $conn->query($sql);
}
