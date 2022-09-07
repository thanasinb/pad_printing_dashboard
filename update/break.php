<?php
require 'establish.php';

// check this is the break-card or not?
$sql = "SELECT id_breakcard FROM breakcard WHERE id_rfid = '" . $_GET["id_rfid"] . "'";
$result = $conn->query($sql);
$data_breakcard = $result->fetch_assoc();

$sql = "SELECT id_activity,status_work FROM activity WHERE id_machine = '" . $_GET["id_mc"] . "'";
$result = $conn->query($sql);
$data_activity = $result->fetch_assoc();

if(empty($data_breakcard)) {
    $data_json = json_encode(array("code"=>"003"), JSON_UNESCAPED_UNICODE);
} else {
// if this is a break-card, update 'status_work=2' in 'activity' table.
    if ($data_activity["status_work"]==2){
        $data_json = json_encode(array("code"=>"009"), JSON_UNESCAPED_UNICODE);
    }
    else{
        $sql = "UPDATE activity SET ";
        $sql = $sql . "status_work=2,";
        $sql = $sql . "time_break=CURTIME()";
        $sql = $sql . " WHERE id_machine='" . $_GET["id_mc"] . "'";;
        $sql = $sql . " AND id_activity=" . $data_activity["id_activity"];
        $result = $conn->query($sql);

        $sql = "UPDATE machine SET ";
        $sql = $sql . "time_contact=CURRENT_TIMESTAMP()";
        $sql = $sql . " WHERE id_mc='" . $_GET["id_mc"] . "'";
        $result = $conn->query($sql);
        $data_json = json_encode(array("code" => "200", "message" => "OK"), JSON_UNESCAPED_UNICODE);
    }
}
print_r($data_json);

require 'terminate.php';

