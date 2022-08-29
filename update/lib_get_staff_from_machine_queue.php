<?php
function get_staff_from_machine_queue($conn, $id_mc)
{
    $sql = "SELECT id_task, id_staff FROM machine_queue WHERE id_machine = '" . $id_mc . "' AND queue_number=1";
    $result = $conn->query($sql);
    $data = $result->fetch_assoc();
    return $data;
}