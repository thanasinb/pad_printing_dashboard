<?php
require 'establish.php';
require '../const-status.php';
require 'lib_get_info_from_activity_type.php';
require 'lib_get_active_activity_by_machine.php';
require 'lib_get_planning.php';
require 'lib_update_count_reset_v4.php';
require 'lib_get_staff_from_machine_queue.php';
require 'lib_get_staff_by_id.php';
require 'lib_get_shif.php';
require 'lib_add_activity.php';
require 'lib_add_activity_downtime.php';
require 'lib_end_activity_idle.php';

#1:     RESET ALL ACTIVITIES BASED ON MACHINE ID
#1.1:   CONSIDER ACTIVITY TABLE, FIND ACTIVE ACTIVITIES BASED ON MACHINE ID
#1.1.1:   IF THE ACTIVITY IS BREAKING, FINISH THE BREAK TABLE FIRST
#1.1.2:   FINISH THE ACTIVITY TABLE
#2:     UPDATE THE MACHINE QUEUE TABLE TO EMPTY
#3:     ADD ACTIVITY BY ACTIVITY TYPE

list($table, $str_activity, $str_status) = get_info_from_activity_type(ACTIVITY_BACKFLUSH);
$data_activity = get_active_activity_by_machine($conn, $table, $str_status, $_GET["id_mc"]);

if(!empty($data_activity)) {

//    if($data_activity[$str_status]==STATUS_BREAK) {
//
//    }

    $data_planning = get_planning($conn, $data_activity['id_task']);
    $data_json = update_count_reset(
        $conn,
        true,
        $table,
        $str_status,
        $str_activity,
        $data_activity);
}

list($table, $str_activity, $str_status) = get_info_from_activity_type(ACTIVITY_REWORK);
$data_activity = get_active_activity_by_machine($conn, $table, $str_status, $_GET["id_mc"]);

if(!empty($data_activity)) {
    $data_planning = get_planning($conn, $data_activity['id_task']);
    $data_json = update_count_reset(
        $conn,
        true,
        $table,
        $str_status,
        $str_activity,
        $data_activity);
}

list($table, $str_activity, $str_status) = get_info_from_activity_type(ACTIVITY_DOWNTIME);
$data_activity = get_active_activity_by_machine($conn, $table, $str_status, $_GET["id_mc"]);

if(!empty($data_activity)) {
    $data_planning = get_planning($conn, $data_activity['id_task']);
    $data_json = update_count_reset(
        $conn,
        true,
        $table,
        $str_status,
        $str_activity,
        $data_activity);
}

$sql = "UPDATE machine_queue SET id_staff='' WHERE id_machine='" . $_GET['id_mc'] . "' AND queue_number=1";
$result = $conn->query($sql);

$data_machine_queue = get_staff_from_machine_queue($conn, $_GET['id_mc']);
$data_staff = get_staff_by_id($conn, $_GET['id_staff']);
list($shif, $date_eff) = get_shif($conn, $_GET['id_staff'], $data_staff['team']);
$data_planning = get_planning($conn, $data_machine_queue['id_task']);

if ($_GET['activity_type']==3){
    $data_json = add_activity_downtime(
        $conn,
        $table,
        $data_machine_queue['id_task'],
        $_GET['id_mc'],
        $_GET['id_staff'],
        $shif,
        $date_eff,
        $_GET['code_downtime'],
        $data_planning['multiplier']);
}else{
    $data_json = add_activity(
        $conn,
        $table,
        $data_machine_queue['id_task'],
        $_GET['id_mc'],
        $_GET['id_staff'],
        $shif,
        $date_eff,
        $data_planning['multiplier']);
}

end_activity_idle($conn, $_GET['id_mc']);

//}elseif ($_GET['activity_type']==2){
//    $table = 'activity_rework';
//    $data_staff = get_staff_by_id($conn, $_GET['id_staff']);
//    list($shif, $date_eff) = get_shif($conn, $_GET['id_staff'], $data_staff['team']);
//    $data_planning = get_planning($conn, $data_machine_queue['id_task']);
//    $data_json = add_activity(
//        $conn,
//        $table,
//        $data_machine_queue['id_task'],
//        $_GET['id_mc'],
//        $_GET['id_staff'],
//        $shif,
//        $date_eff,
//        $data_planning['multiplier']);
//
//    end_activity_idle($conn, $_GET['id_mc']);
//
//}elseif ($_GET['activity_type']==3){
//    $table = 'activity_downtime';
//    $data_staff = get_staff_by_id($conn, $_GET['id_staff']);
//    list($shif, $date_eff) = get_shif($conn, $_GET['id_staff'], $data_staff['team']);
//    $data_planning = get_planning($conn, $data_machine_queue['id_task']);
//    $data_json = add_activity_downtime(
//        $conn,
//        $table,
//        $data_machine_queue['id_task'],
//        $_GET['id_mc'],
//        $_GET['id_staff'],
//        $shif,
//        $date_eff,
//        $_GET['code_downtime'],
//        $data_planning['multiplier']);
//
//    end_activity_idle($conn, $_GET['id_mc']);
//}

print_r($data_json);

require "contact.php";
require 'terminate.php';
