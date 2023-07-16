<?php
require 'establish.php';

const ACTIVITY_BACKFLUSH=1;
const ACTIVITY_REWORK=2;
$sql = "SELECT id_activity, time_start, total_work, total_food, total_toilet, no_send, no_pulse1, CURRENT_TIMESTAMP() AS time_current FROM activity WHERE id_task=" . $_GET["id_task"] . " AND id_machine = '" . $_GET["id_mc"] . "' AND status_work=1";
$result = $conn->query($sql);
$data_activity = $result->fetch_assoc();
if(empty($data_activity)) {
    $sql = "SELECT id_activity, time_start, total_work, total_food, total_toilet, no_send, no_pulse1, CURRENT_TIMESTAMP() AS time_current FROM activity_rework WHERE id_task=" . $_GET["id_task"] . " AND id_machine = '" . $_GET["id_mc"] . "' AND status_work=1";
    $result = $conn->query($sql);
    $data_activity = $result->fetch_assoc();
    if(!empty($data_activity)) { $activity_type=ACTIVITY_REWORK; }
}else{ $activity_type=ACTIVITY_BACKFLUSH; }

$sql = "SELECT divider.divider AS divider FROM divider INNER JOIN planning ON planning.id_task=" . $_GET["id_task"] . " AND divider.op_color=planning.op_color AND divider.op_side=planning.op_side WHERE planning.id_task=" . $_GET["id_task"];
$result = $conn->query($sql);
$data_divider = $result->fetch_assoc();
$divider = floatval($data_divider['divider']);

if(empty($data_activity)) {
    $data_json = json_encode(array("code"=>"007"), JSON_UNESCAPED_UNICODE);
    print_r($data_json);
} else {
//    if (intval($data_activity["no_send"]) < $_GET["no_send"]) {

    $total_food = strtotime("1970-01-01 " . $data_activity["total_food"] . " UTC");
    $total_toilet = strtotime("1970-01-01 " . $data_activity["total_toilet"] . " UTC");
    $total_break = $total_food;
    $time_start = strtotime($data_activity["time_start"]);
    $time_current = strtotime($data_activity["time_current"]);
    $time_total_second = $time_current-$time_start-$total_break;
    $time_total =  gmdate('H:i:s', $time_total_second);
    if($_GET["no_pulse1"]==0){
        $run_time_actual=0.0;
    }else{
        $run_time_actual = round($time_total_second/(($_GET['no_pulse1'] + intval($data_activity['no_pulse1']))*$divider), 2);
    }

//    echo $total_food . "<br>";
//    echo $total_toilet . "<br>";
//    echo $total_break . "<br>";
//    echo $time_start . "<br>";
//    echo $time_current . "<br>";
//    echo $time_total . "<br>";
//    echo $run_time_actual . "<br>";

    if($activity_type==ACTIVITY_BACKFLUSH){
        $sql = "UPDATE activity SET ";
    }elseif ($activity_type==ACTIVITY_REWORK){
        $sql = "UPDATE activity_rework SET ";
    }
    $sql = $sql . "status_work=1,";
    $sql = $sql . "total_work='" . $time_total . "',";
    $sql = $sql . "run_time_actual=" . $run_time_actual . ",";
    $sql = $sql . "no_send=" . $_GET["no_send"] . ",";
    $sql = $sql . "no_pulse1=" . $_GET["no_pulse1"] . ",";
    $sql = $sql . "no_pulse2=" . $_GET["no_pulse2"] . ",";
    $sql = $sql . "no_pulse3=" . $_GET["no_pulse3"];
    $sql = $sql . " WHERE id_activity=" . $data_activity["id_activity"];
    $result = $conn->query($sql);

//    echo $sql . "<br>";

//    $sql = "UPDATE machine SET ";
//    $sql = $sql . "time_contact=CURRENT_TIMESTAMP()";
//    $sql = $sql . " WHERE id_mc='" . $_GET["id_mc"] . "'";
//    $result = $conn->query($sql);
    echo "OK";

//    }else{
//        $data_json = json_encode(array("code"=>"002"), JSON_UNESCAPED_UNICODE);
//        print_r($data_json);
//    }
}
require "contact.php";
require 'terminate.php';

