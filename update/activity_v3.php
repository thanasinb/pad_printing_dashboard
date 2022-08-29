<?php
require 'establish.php';
require 'lib_get_shif.php';
require 'lib_get_staff_by_id.php';
require 'lib_add_activity.php';

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
}else{
    $data_json = json_encode(array('code'=>'005', 'message'=>'Invalid activity_type'), JSON_UNESCAPED_UNICODE);
}

print_r($data_json);

require "contact.php";
require 'terminate.php';
