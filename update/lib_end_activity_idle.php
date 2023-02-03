<?php

function end_activity_idle($conn, $id_machine)
{
    $sql = "SELECT *, CURRENT_TIMESTAMP() AS time_current FROM activity_idle WHERE status_work=1 AND id_machine='" . $id_machine . "'";
    $result = $conn->query($sql);
    $data_activity = $result->fetch_assoc();

    if (!empty($data_activity)){
        $time_current = strtotime($data_activity["time_current"]);
        $time_start = strtotime($data_activity["time_start"]);
        $time_duration_second = $time_current - $time_start;
        $time_duration =  gmdate('H:i:s', $time_duration_second);

        $sql = "UPDATE activity_idle SET ";
        $sql = $sql . "status_work=3,";
        $sql = $sql . "time_end='" . $data_activity["time_current"] . "',";
        $sql = $sql . "duration='" . $time_duration . "'";
        $sql = $sql . " WHERE id_activity=" . $data_activity["id_activity"];
        $result = $conn->query($sql);
    }
}