<?php
require 'lib_get_staff_by_rfid.php';
require 'lib_get_info_from_activity_type.php';
require 'lib_get_active_activity_by_activity_id_type_staff_and_machine.php';
require 'lib_update_count.php';
require 'lib_quit.php';
require 'establish.php';

$data_json = quit($conn,
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

require "contact.php";
require 'terminate.php';

print_r($data_json);
