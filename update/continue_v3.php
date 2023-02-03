<?php
require 'establish.php';
require 'lib_get_break_info_from_activity_type.php';
require 'lib_get_break_by_id.php';
require 'lib_get_break_activity_and_time_by_id_and_machine.php';
require 'lib_update_break.php';
require 'lib_add_log.php';

add_log($conn, basename($_SERVER['REQUEST_URI']));
list($table, $table_break, $str_activity, $str_status) = get_break_info_from_activity_type($_GET['activity_type']);
$data_break = get_break_by_id($conn, $table_break, $_GET['id_break']);

if(empty($data_break)){
    $data_json = json_encode(array('code'=>'009', 'message'=>'Invalid break ID'), JSON_UNESCAPED_UNICODE);
}
else{
    $data_activity = get_break_activity_and_time_by_id_and_machine(
        $conn,
        $table,
        $str_activity,
        $str_status,
        $_GET['id_activity'],
        $_GET['id_mc']);
    if(empty($data_activity)){
        $data_json = json_encode(array('code'=>'010', 'message'=>'No break activity found'), JSON_UNESCAPED_UNICODE);
    }
    else{
        $break_code = intval($data_break["break_code"]);
        // BREAK CODE: 1=FOOD, 2=TOILET
        if($break_code==1){
            $total_break = strtotime("1970-01-01 " . $data_activity["total_food"] . " UTC");
            $str_break = 'total_food';
        }elseif ($break_code==2){
            $total_break = strtotime("1970-01-01 " . $data_activity["total_toilet"] . " UTC");
            $str_break = 'total_toilet';
        }

        $time_break = strtotime($data_break["time_break"]);
        $time_current = strtotime($data_activity["time_current"]);
        $break_duration = $time_current-$time_break;
        $break_duration_text = gmdate('H:i:s', $break_duration);
        $total_break_text =  gmdate('H:i:s', $break_duration + $total_break);

        update_break($conn, $table, $table_break, $str_activity, $str_status, $_GET['id_activity'], $_GET['id_break'],
            $data_activity["time_current"], $break_duration_text, $str_break, $total_break_text);

        $data_json = json_encode(array("total_break"=>$total_break_text), JSON_UNESCAPED_UNICODE);
    }
}

print_r($data_json);

require "contact.php";
require 'terminate.php';

