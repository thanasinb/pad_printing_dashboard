<?php

function add_activity_idle($conn, $id_machine, $time_start)
{
    $sql = "INSERT INTO activity_idle (
    id_machine, 
    status_work, 
    time_start) VALUES (";
    $sql = $sql . "'" . $id_machine . "',";
    $sql = $sql . "1,";
    $sql = $sql . "'" . $time_start . "'";
    $sql = $sql . ")";
    $result = $conn->query($sql);
}