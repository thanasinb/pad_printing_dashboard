<?php
require 'establish.php';
require 'lib_update_count.php';
require 'lib_get_staff_from_machine_queue.php';
require 'lib_get_active_activity_by_activity_id_type_staff_and_machine.php';
require 'lib_get_info_from_activity_type.php';
require 'lib_add_log.php';

add_log($conn, basename($_SERVER['REQUEST_URI']));
$data_machine_queue = get_staff_from_machine_queue($conn, $_GET['id_mc']);

if($data_machine_queue['id_staff'] == null) {
    $data_json = json_encode(array('code'=>'007', 'message'=>'Machine is idle, cannot update data'), JSON_UNESCAPED_UNICODE);
}
else{
    list($table, $str_activity, $str_status) = get_info_from_activity_type($_GET['activity_type']);
    $data_activity = get_active_activity_by_activity_id_type_staff_and_machine(
        $conn,
        $table,
        $str_activity,
        $str_status,
        $_GET['id_activity'],
        $data_machine_queue['id_staff'],
        $_GET['id_mc']);
    if (empty($data_activity)){
        $data_json = json_encode(array('code'=>'025', 'message'=>'No active activity found for specified staff and machine'), JSON_UNESCAPED_UNICODE);
    }
    else{
        $is_quit = false;
        $status_work = 1;
        $data_json = update_count($conn, $is_quit, $table, $status_work,
            $_GET['id_activity'],
            $_GET['no_send'],
            $_GET['no_pulse1'],
            $_GET['no_pulse2'],
            $_GET['no_pulse3'],
            $_GET['multiplier']);
    }
}

print_r($data_json);

require "contact.php";
require 'terminate.php';

