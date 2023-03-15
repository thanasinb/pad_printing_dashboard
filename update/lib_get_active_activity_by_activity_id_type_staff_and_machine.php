<?php
function get_active_activity_by_activity_id_type_staff_and_machine($conn, $table, $str_activity, $str_status, $id_activity, $id_staff, $id_mc)
{
    $sql = "SELECT *, CURRENT_TIMESTAMP() AS time_current FROM " . $table . " WHERE " .
        $str_activity . "=" . $id_activity . " AND 
        id_staff = '" . $id_staff . "' AND 
        id_machine = '" . $id_mc . "' AND " .
        $str_status . "=1";

        echo $sql . "<br>";

        $result = $conn->query($sql);
        $data = $result->fetch_assoc();
        return $data;
}