<?php
function add_activity($conn, $table, $id_task, $id_machine, $id_staff, $shif, $date_eff){
    $sql = "INSERT INTO " . $table . " (id_task, id_machine, id_staff, shif, date_eff, status_work, time_start) VALUES (";
    $sql = $sql . $id_task . ",";
    $sql = $sql . "'" . $id_machine . "',";
    $sql = $sql . "'" . $id_staff . "',";
    $sql = $sql . "'" . $shif . "',";
    $sql = $sql . "'" . $date_eff . "',";
    $sql = $sql . "1,";
    $sql = $sql . "CURRENT_TIMESTAMP()";
    $sql = $sql . ")";
    $result = $conn->query($sql);

    $sql = 'SELECT LAST_INSERT_ID() as id_activity';
    $result = $conn->query($sql);
    $data_activity = $result->fetch_assoc();
    $data_json = json_encode(($data_activity), JSON_UNESCAPED_UNICODE);

    $sql = "UPDATE machine_queue SET id_staff='" . $id_staff . "' WHERE id_machine='" . $id_machine . "' AND queue_number=1";
    $result = $conn->query($sql);

    return $data_json;
}
