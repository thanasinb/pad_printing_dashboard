<?php
function add_log($conn, $message)
{
    $dt = new DateTime('NOW');
    $dt_text = $dt->format('Y-m-d H:i:s');

    $sql = "INSERT INTO log (log_datetime, log_description) VALUES ('" . $dt_text . "', '". $message . "')";
    $conn->query($sql);
}
