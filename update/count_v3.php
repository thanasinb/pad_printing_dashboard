<?php
require 'establish.php';
require 'lib_update_count.php';
require 'lib_get_staff_from_machine_queue.php';

$data_machine_queue = get_staff_from_machine_queue($conn, $_GET['id_mc']);
if($data_machine_queue['id_staff'] == null) {
    $data_json = json_encode(array('code'=>'007', 'message'=>'Machine is idle, cannot update data'), JSON_UNESCAPED_UNICODE);
}
else{
    if($_GET['activity_type']==1){
        $table = 'activity';
        $data_json = update_count($conn, $table,
            $_GET['id_activity'],
            $_GET['no_send'],
            $_GET['no_pulse1'],
            $_GET['no_pulse2'],
            $_GET['no_pulse3'],
            $_GET['multiplier']);
    }elseif ($_GET['activity_type']==2){
        $table = 'activity_rework';
        $data_json = update_count($conn, $table,
            $_GET['id_activity'],
            $_GET['no_send'],
            $_GET['no_pulse1'],
            $_GET['no_pulse2'],
            $_GET['no_pulse3'],
            $_GET['multiplier']);
    }elseif ($_GET['activity_type']==3){
        $table = 'activity_downtime';
        $data_json = update_count($conn, $table,
            $_GET['id_activity'],
            $_GET['no_send'],
            $_GET['no_pulse1'],
            $_GET['no_pulse2'],
            $_GET['no_pulse3'],
            $_GET['multiplier']);
    }else{
        $data_json = json_encode(array('code'=>'002', 'message'=>'Invalid activity_type'), JSON_UNESCAPED_UNICODE);
    }
}

print_r($data_json);

require "contact.php";
require 'terminate.php';

