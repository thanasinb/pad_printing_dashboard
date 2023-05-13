<?php
require 'establish.php';
require 'lib_get_staff_by_rfid.php';
require 'lib_get_info_from_activity_type.php';
require 'lib_get_active_activity_by_activity_id_type_staff_and_machine.php';
require 'lib_update_count.php';
require 'lib_quit.php';
require 'lib_add_log.php';
require 'lib_add_activity_idle.php';


add_log($conn, basename($_SERVER['REQUEST_URI']));

// Replace $_GET['multiplier'] by 1 (dummy) until the box is ready

$data_json = quit($conn,
    $_GET['id_mc'],
    $_GET['id_rfid'],
    $_GET['id_activity'],
    $_GET['activity_type'],
    $_GET['no_send'],
    $_GET['no_pulse1'],
    $_GET['no_pulse2'],
    $_GET['no_pulse3'],
    1);

$data = json_decode($data_json, true);
if($data['quit_complete']){
    add_activity_idle($conn, $_GET['id_mc'], $data['time_current']);
}

require "contact.php";
require 'terminate.php';

if(strcmp($_GET['dashboard'],'1')==0){
    header("Location: ../pp-machine.php");
    die();
}
