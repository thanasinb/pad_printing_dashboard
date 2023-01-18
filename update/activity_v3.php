<?php
require 'establish.php';
require 'lib_get_shif.php';
require 'lib_get_staff_by_id.php';
require 'lib_add_activity.php';
require 'lib_add_activity_downtime.php';
require 'lib_get_staff_from_machine_queue.php';
require 'lib_get_active_activity_by_id_and_machine.php';
require 'lib_add_log.php';

add_log($conn, basename($_SERVER['REQUEST_URI']));

$data_machine_queue = get_staff_from_machine_queue($conn, $_GET['id_mc']);
if(empty($data_machine_queue['id_staff'])){
    if($_GET['activity_type']==1){
        $table = 'activity';
        $data_staff = get_staff_by_id($conn, $_GET['id_staff']);
        list($shif, $date_eff) = get_shif($conn, $_GET['id_staff'], $data_staff['team']);
        $data_json = add_activity($conn, $table, $_GET['id_task'], $_GET['id_mc'], $_GET['id_staff'], $shif, $date_eff);
    }elseif ($_GET['activity_type']==2){
        $table = 'activity_rework';
        $data_staff = get_staff_by_id($conn, $_GET['id_staff']);
        list($shif, $date_eff) = get_shif($conn, $_GET['id_staff'], $data_staff['team']);
        $data_json = add_activity($conn, $table, $_GET['id_task'], $_GET['id_mc'], $_GET['id_staff'], $shif, $date_eff);
    }elseif ($_GET['activity_type']==3){
        $table = 'activity_downtime';
        $data_machine_queue = get_staff_from_machine_queue($conn, $_GET['id_mc']);
//    if(!empty($data_machine_queue)){
//        $data_existing_staff = get_staff_by_id($conn, $data_machine_queue['id_staff']);
//        if(intval($data_existing_staff['role']) == 1){
//            $data_existing_activity = get_active_activity_by_id_and_machine($conn, $table, $data_existing_staff['id_staff'], $_GET['id_mc']);
//            $_GET['id_rfid'] = $data_existing_staff['id_rfid'];
//            $_GET['id_activity'] = $data_existing_activity['id_activity'];
//            $_GET['no_send'] = 9999;
//            $_GET['no_pulse1'] = 0;
//            $_GET['no_pulse2'] = 0;
//            $_GET['no_pulse3'] = 0;

//              PARAMS FOR QUIT:
//              ID_ACTIVITY: Y,
//              ACTIVITY_TYPE: Y,
//              ID_RFID: Y,
//              ID_MC: Y,
//              NO_SEND:
//            require 'quit_v3.php';
//        }
//    }
        $data_staff = get_staff_by_id($conn, $_GET['id_staff']);
        list($shif, $date_eff) = get_shif($conn, $_GET['id_staff'], $data_staff['team']);
        $data_json = add_activity_downtime($conn, $table, $_GET['id_task'], $_GET['id_mc'], $_GET['id_staff'], $shif, $date_eff, $_GET["code_downtime"]);
    }else{
        $data_json = json_encode(array('code'=>'005', 'message'=>'Invalid activity_type'), JSON_UNESCAPED_UNICODE);
    }
}else{
    $data_json = json_encode(array('code'=>'008', 'message'=>'Machine is busy'), JSON_UNESCAPED_UNICODE);
}

print_r($data_json);

require "contact.php";
require 'terminate.php';
