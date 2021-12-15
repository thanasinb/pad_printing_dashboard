<?php
//require 'establish.php';
require '../const-shif.php';

function find_shif(){
//    $time_touch_stamp = strtotime($time_touch);
    $current_date_stamp = strtotime(date("Y/m/d"));
    echo $current_date_stamp;
//    return $shif;
}

$sql = "SELECT id_staff FROM machine_queue WHERE id_machine='" . $_GET["id_mc"] . "' AND queue_number=1";
echo $sql . "<br>";
//$query_queue = $conn->query($sql);
//$data_queue = $query_queue->fetch_assoc();
//if ($data_queue['id_staff']==null) {
//    $sql = "SELECT id_staff, name_first, name_last, id_role as role, id_shif FROM staff WHERE id_rfid='" . $_GET['id_rfid'] . "'";
////    echo $sql . "<br>";
//    $result = $conn->query($sql);
//    $data_staff = $result->fetch_assoc();
//    $data_staff['role']=intval($data_staff['role']);
//
//    $sql = "SELECT id_task FROM planning WHERE id_job='" . $_GET['id_job'] . "' AND operation='" . $_GET['operation'] . "'";
////    echo $sql . "<br>";
////    find_shif();
//    $result = $conn->query($sql);
//    $data_planning_id_task = $result->fetch_assoc();
//
////    echo $data_staff['role'] . "<br>";
//    if($_GET['activity_type']==1){
////    if($data_staff['role']==1){
//        $sql = "INSERT INTO activity (id_task, id_machine, id_staff, status_work, time_start) VALUES (";
//        $sql = $sql . $data_planning_id_task["id_task"] . ",";
//        $sql = $sql . "'" . $_GET["id_mc"] . "',";
//        $sql = $sql . "'" . $data_staff["id_staff"] . "',";
//        $sql = $sql . "1,";
//        $sql = $sql . "CURRENT_TIMESTAMP()";
//        $sql = $sql . ")";
////        echo $sql . "<br>";
//        $result = $conn->query($sql);
//
//        // UPDATE STAFF ID IN MACHINE QUEUE TABLE
//        $sql = "UPDATE machine_queue SET id_staff='" . $data_staff['id_staff'] . "' WHERE id_machine='" . $_GET["id_mc"] . "' AND queue_number=1";
//        $result = $conn->query($sql);
////        echo "OK";
//    }elseif ($_GET['activity_type']==2){
////    }elseif ($data_staff['role']==2){
//        $sql = "INSERT INTO activity_downtime (id_task, id_machine, id_staff, status_work, time_start) VALUES (";
//        $sql = $sql . $data_planning_id_task["id_task"] . ",";
//        $sql = $sql . "'" . $_GET["id_mc"] . "',";
//        $sql = $sql . "'" . $data_staff["id_staff"] . "',";
//        $sql = $sql . "1,";
//        $sql = $sql . "CURRENT_TIMESTAMP()";
//        $sql = $sql . ")";
////        echo $sql . "<br>";
//        $result = $conn->query($sql);
//        $data_staff["role"]=intval($data_staff["role"]);
//
//        // UPDATE STAFF ID IN MACHINE QUEUE TABLE
//        $sql = "UPDATE machine_queue SET id_staff='" . $data_staff['id_staff'] . "' WHERE id_machine='" . $_GET["id_mc"] . "' AND queue_number=1";
//        $result = $conn->query($sql);
////        echo "OK";
//    }
//}else{
//    $data_json = json_encode(array("code"=>"025", "message"=>"This machine is currently in occupied by staff ID: " . $data_queue['id_staff']), JSON_UNESCAPED_UNICODE);
////    print_r($data_json);
//}

require "contact.php";
//require 'terminate.php';
