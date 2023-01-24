<?php
require 'lib_quit.php';

require 'establish.php';

quit($conn,
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

