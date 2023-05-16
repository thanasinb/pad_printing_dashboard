<?php
require 'establish.php';
require 'lib_get_staff_from_machine_queue.php';
require 'lib_get_staff_by_id.php';
require 'lib_get_shif.php';
require 'lib_get_planning.php';
require 'lib_get_info_from_activity_type.php';
require 'lib_add_activity.php';
require 'lib_add_activity_downtime.php';
require 'lib_get_staff_by_rfid.php';
require 'lib_get_active_activity_by_activity_id_type_staff_and_machine.php';
require 'lib_update_count.php';
require 'lib_quit.php';
require 'lib_add_log.php';

add_log($conn, basename($_SERVER['REQUEST_URI']));
$data_machine_queue = get_staff_from_machine_queue($conn, $_GET['id_mc']);

$data_json_1 = quit($conn,
    $_GET['id_mc'],
    $_GET['id_rfid'],
    $_GET['id_activity'],
    $_GET['activity_type'],
    $_GET['no_send'],
    $_GET['no_pulse1'],
    $_GET['no_pulse2'],
    $_GET['no_pulse3'],
    $_GET['multiplier'],
    $_GET['code_downtime)']);

$_GET['id_staff'] = $data_machine_queue['id_staff'];
$_GET['id_task'] = $data_machine_queue['id_task'];
$_GET['activity_type'] = $_GET['activity_type_new'];

$data_staff = get_staff_by_id($conn, $_GET['id_staff']);
list($shif, $date_eff) = get_shif($conn, $_GET['id_staff'], $data_staff['team']);
$data_planning = get_planning($conn, $_GET['id_task']);
list($table, $str_activity, $str_status) = get_info_from_activity_type($_GET['activity_type']);
if($_GET['activity_type']==3){
    $data_json_2 = add_activity_downtime(
        $conn,
        $table,
        $_GET['id_task'],
        $_GET['id_mc'],
        $_GET['id_staff'],
        $shif,
        $date_eff,
        $_GET['code_downtime'],
        $data_planning['multiplier']);
}else{
    $data_json_2 = add_activity(
        $conn,
        $table,
        $_GET['id_task'],
        $_GET['id_mc'],
        $_GET['id_staff'],
        $shif,
        $date_eff,
        $data_planning['multiplier'],
        $_GET['activity_type']);
}

require 'terminate.php';

$data_json = json_encode(array_merge(json_decode($data_json_1, true),json_decode($data_json_2, true)));
print_r($data_json);
