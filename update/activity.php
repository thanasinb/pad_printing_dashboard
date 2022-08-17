<?php
require 'establish.php';
require 'find_shif.php';

$sql = "SELECT id_staff, id_task FROM machine_queue WHERE id_machine='" . $_GET["id_mc"] . "' AND queue_number=1";
$query_queue = $conn->query($sql);
$data_queue = $query_queue->fetch_assoc();
if ($data_queue['id_staff']==null) {
    $sql = "SELECT id_staff, name_first, name_last, id_role as role, id_shif as team FROM staff WHERE id_rfid='" . $_GET['id_rfid'] . "'";
    $result = $conn->query($sql);
    $data_staff = $result->fetch_assoc();
    $data_staff['role']=intval($data_staff['role']);

    list($shif, $date_eff) = find_shif($conn, $data_staff['id_staff'], $data_staff['team']);

    if($_GET['activity_type']==1){
        $sql = "INSERT INTO activity (id_task, id_machine, id_staff, shif, date_eff, status_work, time_start) VALUES (";
        $sql = $sql . $data_queue["id_task"] . ",";
        $sql = $sql . "'" . $_GET["id_mc"] . "',";
        $sql = $sql . "'" . $data_staff["id_staff"] . "',";
        $sql = $sql . "'" . $shif . "',";
        $sql = $sql . "'" . $date_eff . "',";
        $sql = $sql . "1,";
        $sql = $sql . "CURRENT_TIMESTAMP()";
        $sql = $sql . ")";
        $result = $conn->query($sql);

        // UPDATE STAFF ID IN MACHINE QUEUE TABLE
        $sql = "UPDATE machine_queue SET id_staff='" . $data_staff['id_staff'] . "' WHERE id_machine='" . $_GET["id_mc"] . "' AND queue_number=1";
        $result = $conn->query($sql);
//        echo "OK";
    }elseif ($_GET['activity_type']==2){
        $sql = "INSERT INTO activity_rework (id_task, id_machine, id_staff, shif, date_eff, status_work, time_start) VALUES (";
        $sql = $sql . $data_queue["id_task"] . ",";
        $sql = $sql . "'" . $_GET["id_mc"] . "',";
        $sql = $sql . "'" . $data_staff["id_staff"] . "',";
        $sql = $sql . "'" . $shif . "',";
        $sql = $sql . "'" . $date_eff . "',";
        $sql = $sql . "1,";
        $sql = $sql . "CURRENT_TIMESTAMP()";
        $sql = $sql . ")";
        $result = $conn->query($sql);

        // UPDATE STAFF ID IN MACHINE QUEUE TABLE
        $sql = "UPDATE machine_queue SET id_staff='" . $data_staff['id_staff'] . "' WHERE id_machine='" . $_GET["id_mc"] . "' AND queue_number=1";
        $result = $conn->query($sql);
//        echo "OK";
    }
    $sql = 'SELECT LAST_INSERT_ID() as id_activity';
    $result = $conn->query($sql);
    $data_activity = $result->fetch_assoc();
    $data_json = json_encode(($data_activity), JSON_UNESCAPED_UNICODE);

}else{
    $data_json = json_encode(array("code"=>"025", "message"=>"This machine is currently in occupied by staff ID: " . $data_queue['id_staff']), JSON_UNESCAPED_UNICODE);
//    print_r($data_json);
}

print_r($data_json);

require "contact.php";
require 'terminate.php';
