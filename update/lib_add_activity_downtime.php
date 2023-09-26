<?php
function add_activity_downtime($conn, $table, $id_task, $id_machine, $id_staff, $shif, $date_eff, $code_downtime, $multiplier){
    $sql = "SELECT id_downtime FROM code_downtime WHERE id_code_downtime='" . $code_downtime . "'";
    $data_id_downtime = $conn->query($sql)->fetch_assoc();
//    $data_id_downtime = $query_code_downtime->fetch_assoc();

    if (empty($data_id_downtime['id_downtime'])) {
        $data_json = json_encode(array("code" => "015", "message" => "Invalid downtime code"), JSON_UNESCAPED_UNICODE);
    }
    else{
        $sql = "INSERT INTO " . $table;
        $sql = $sql . " (
        id_task, 
        id_machine, 
        id_staff, 
        shif, 
        date_eff, 
        id_downtime, 
        status_downtime, 
        time_start, 
        time_previous, 
        multiplier) 
        VALUES (";
        $sql = $sql . $id_task . ",";
        $sql = $sql . "'" . $id_machine . "',";
        $sql = $sql . "'" . $id_staff . "',";
        $sql = $sql . "'" . $shif . "',";
        $sql = $sql . "'" . $date_eff . "',";
        $sql = $sql . $data_id_downtime['id_downtime'] . ",";
        $sql = $sql . "1,";
        $sql = $sql . "CURRENT_TIMESTAMP(),";
        $sql = $sql . "CURRENT_TIMESTAMP(),";
        $sql = $sql . $multiplier;
        $sql = $sql . ")";
        $result = $conn->query($sql);

        $sql = 'SELECT LAST_INSERT_ID() as id_activity';
        $result = $conn->query($sql);
        $data_activity = $result->fetch_assoc();
        $data_json = json_encode(($data_activity), JSON_UNESCAPED_UNICODE);

        $sql = "UPDATE machine_queue SET id_staff='" . $id_staff . "' WHERE id_machine='" . $id_machine . "' AND queue_number=1";
        $result = $conn->query($sql);
    }

    return $data_json;
}
