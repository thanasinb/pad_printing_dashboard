<?php
function add_break($conn, $table, $id_task, $id_machine, $id_staff, $shif, $date_eff){
    $sql = "INSERT INTO " . $table_break . " (id_activity, id_staff, break_code, break_start) VALUES (";
    $sql = $sql . $data_activity["id_activity"] . ",";
    $sql = $sql . "'" . $data_staff["id_staff"] . "',";
    $sql = $sql . $_GET["break_code"] . ",";
    $sql = $sql . "CURRENT_TIMESTAMP())";
    $result = $conn->query($sql);

    $sql = 'SELECT LAST_INSERT_ID() as id_activity';
    $result = $conn->query($sql);
    $data_activity = $result->fetch_assoc();
    $data_json = json_encode(($data_activity), JSON_UNESCAPED_UNICODE);

    $sql = "UPDATE " . $table_activity . " SET ";
    $sql = $sql . "id_break=" . $id_break . ",";
    $sql = $sql . "status_work=2";
    $sql = $sql . " WHERE id_machine='" . $_GET["id_mc"] . "'";;
    $sql = $sql . " AND id_activity=" . $data_activity["id_activity"];
    $result = $conn->query($sql);

    return $data_json;
}
