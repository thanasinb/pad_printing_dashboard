<?php
require 'establish.php';

const ACTIVITY_BACKFLUSH=1;
const ACTIVITY_REWORK=2;

$sql = "SELECT id_activity, id_break, total_food, total_toilet, CURRENT_TIMESTAMP() AS time_current FROM activity WHERE status_work=2 AND id_machine='" . $_GET['id_mc'] . "'";
$result = $conn->query($sql);
$data_activity = $result->fetch_assoc();
if(empty($data_activity)) {
    $sql = "SELECT id_activity, id_break, total_food, total_toilet, CURRENT_TIMESTAMP() AS time_current FROM activity_rework WHERE status_work=2 AND id_machine='" . $_GET['id_mc'] . "'";
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

$sql = "SELECT break_code, break_start AS time_break FROM " . $table_break . " WHERE id_break=" . $data_activity['id_break'];
//echo $sql;
$result = $conn->query($sql);
$data_break = $result->fetch_assoc();

if(empty($data_activity)) {
    $data_json = json_encode(array("code"=>"010"), JSON_UNESCAPED_UNICODE);
    print_r($data_json);
}else{
    $break_code = intval($data_break["break_code"]);
    // BREAK CODE: 2=FOOD, 1=TOILET
    if($break_code==2){
        $total_break = strtotime("1970-01-01 " . $data_activity["total_food"] . " UTC");
    }elseif ($break_code==1){
        $total_break = strtotime("1970-01-01 " . $data_activity["total_toilet"] . " UTC");
    }

    $time_break = strtotime($data_break["time_break"]);
    $time_current = strtotime($data_activity["time_current"]);
    $break_duration = $time_current-$time_break;
    $break_duration_text = gmdate('H:i:s', $break_duration);
    $total_break_text =  gmdate('H:i:s', $break_duration + $total_break);

    // BREAK CODE: 2=FOOD, 1=TOILET
    if($break_code==2){
        $sql = "UPDATE " . $table_activity . " SET status_work=1, total_food='" . $total_break_text . "' WHERE id_activity=" . $data_activity["id_activity"];
    }elseif ($break_code==1) {
        $sql = "UPDATE " . $table_activity . " SET status_work=1, total_toilet='" . $total_break_text . "' WHERE id_activity=" . $data_activity["id_activity"];
    }
    $result = $conn->query($sql);

    $sql = "UPDATE " . $table_break . " SET " . "break_stop='" . $data_activity["time_current"] . "', " . "break_duration='" . $break_duration_text . "' " . "WHERE id_break=" . $data_activity["id_break"];
    $result = $conn->query($sql);

    $data_json = json_encode(array("total_break"=>$total_break_text), JSON_UNESCAPED_UNICODE);
    print_r($data_json);
}

require "contact.php";
require 'terminate.php';

