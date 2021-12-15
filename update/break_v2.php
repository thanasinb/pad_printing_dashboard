<?php
require 'establish.php';

const ACTIVITY_BACKFLUSH=1;
const ACTIVITY_REWORK=2;

// CHECK THIS IS A BREAK-CARD OR NOT?
$sql = "SELECT id_staff FROM staff WHERE id_rfid = '" . $_GET["id_rfid"] . "'";
$result = $conn->query($sql);
$data_staff = $result->fetch_assoc();

if(empty($data_staff)) {
    $data_json = json_encode(array("code"=>"013"), JSON_UNESCAPED_UNICODE);
    print_r($data_json);
} else {

    $sql = "SELECT id_activity, time_start FROM activity WHERE id_machine = '" . $_GET["id_mc"] . "' AND id_staff='" . $data_staff["id_staff"] . "' AND status_work=1";
    $result = $conn->query($sql);
    $data_activity = $result->fetch_assoc();
    if(empty($data_activity)) {
        $sql = "SELECT id_activity, time_start FROM activity_rework WHERE id_machine = '" . $_GET["id_mc"] . "' AND id_staff='" . $data_staff["id_staff"] . "' AND status_work=1";
        $result = $conn->query($sql);
        $data_activity = $result->fetch_assoc();
        if(!empty($data_activity)) { $activity_type=ACTIVITY_REWORK; }
    }else{ $activity_type=ACTIVITY_BACKFLUSH; }

    if($activity_type==ACTIVITY_BACKFLUSH){
        $table_break='break';
        $table_activity='activity';
    }elseif ($activity_type==ACTIVITY_REWORK) {
        $table_break='break_rework';
        $table_activity='activity_rework';
    }

//    $sql = "SELECT id_activity, time_start FROM activity ";
//    $sql = $sql . "WHERE id_machine = '" . $_GET["id_mc"] . "' AND id_staff='" . $data_staff["id_staff"] . "' AND status_work=1";
    $result = $conn->query($sql);
    $data_activity = $result->fetch_assoc();

    if(empty($data_activity)) {
        $data_json = json_encode(array("code"=>"014"), JSON_UNESCAPED_UNICODE);
        print_r($data_json);
    }else{
        $sql = "INSERT INTO " . $table_break . " (id_activity, id_staff, break_code, break_start) VALUES (";
        $sql = $sql . $data_activity["id_activity"] . ",";
        $sql = $sql . "'" . $data_staff["id_staff"] . "',";
        $sql = $sql . $_GET["break_code"] . ",";
        $sql = $sql . "CURRENT_TIMESTAMP())";
        $result = $conn->query($sql);

        $sql = "SELECT id_break FROM " . $table_break . " WHERE ";
        $sql = $sql . "id_activity=" . $data_activity["id_activity"] . " AND ";
        $sql = $sql . "id_staff='" . $data_staff["id_staff"] . "' AND ";
        $sql = $sql . "break_duration='00:00:00'";
        $result = $conn->query($sql);
        $data_break = $result->fetch_assoc();
        $id_break = $data_break["id_break"];

        $sql = "UPDATE " . $table_activity . " SET ";
        $sql = $sql . "id_break=" . $id_break . ",";
        $sql = $sql . "status_work=2";
        $sql = $sql . " WHERE id_machine='" . $_GET["id_mc"] . "'";;
        $sql = $sql . " AND id_activity=" . $data_activity["id_activity"];
        $result = $conn->query($sql);

        echo "OK";
    }
}
require "contact.php";
require 'terminate.php';

