<?php
//require 'establish.php';
//require '../const-shif.php';

function find_shif($conn, $id_staff, $team){

    if(strcmp($team, 'A' == 0)){
        $team_no = '4';
    }elseif (strcmp($team, 'B' == 0)){
        $team_no = '6';
    }elseif (strcmp($team, 'C' == 0)) {
        $team_no = '5';
    }

    $time_current = time();
//    $dummy_date = new DateTime();
//    $dummy_date->setDate(2021, 11, 3);
//    $dummy_date->setTime(16, 00, 00);
//    $time_current = $dummy_date->getTimestamp();
//    echo $time_current;

    $today_00_00 = strtotime(date("Y-m-d", $time_current));
//    echo $today_00_00;

    $day_1 = 24*60*60;
    $today_07_00 = $today_00_00 + (7*60*60);
    $today_15_45 = $today_00_00 + (15*60*60) + (45*60);
    $today_19_00 = $today_00_00 + (19*60*60);
    $today_23_00 = $today_00_00 + (23*60*60);
    $tomorrow_00_00 = $today_00_00 + $day_1;
    $yesterday_19_00 = $today_00_00 + (19*60*60) - $day_1;
    $yesterday_23_00 = $today_00_00 + (23*60*60) - $day_1;

    //NIGHT SHIF BTW 00:00 AND 07:00, NEED TO CHECK YESTERDAY 19:00-23:00 FOR OT
    if ($today_00_00<$time_current AND $time_current<$today_07_00){
        $sql = "SELECT id_activity FROM activity WHERE id_staff='" . $id_staff . "'" .
            " AND (time_start BETWEEN '" . date("Y-m-d H:i:s", $yesterday_19_00) . "' AND '" . date("Y-m-d H:i:s", $yesterday_23_00) . "')";
        $result = $conn->query($sql);
        $data_activity = $result->fetch_assoc();
        if (empty($data_activity)){
            $shif = "N" . $team_no;
        }else{
            $shif = $team_no . "N";
        }
        //DAY SHIF BTW 07:00 AND 15:45
    }elseif ($today_07_00<$time_current AND $time_current<$today_15_45){
        $shif = "D" . $team_no;
    }elseif ($today_15_45<$time_current AND $time_current<$today_19_00){
        $shif = $team_no . "D";
        $sql = "UPDATE activity SET shif='" . $shif . "' WHERE id_staff='" . $id_staff . "'" .
            " AND (time_start BETWEEN '" . date("Y-m-d H:i:s", $today_07_00) . "' AND '" . date("Y-m-d H:i:s", $today_15_45) . "')";
        $conn->query($sql);
//        echo $sql;
    }elseif ($today_19_00<$time_current AND $time_current<$today_23_00){
        $shif = $team_no . "N";
    }elseif ($today_23_00<$time_current AND $time_current<$tomorrow_00_00){
        $sql = "SELECT id_activity FROM activity WHERE id_staff='" . $id_staff . "'" .
            " AND (time_start BETWEEN '" . date("Y-m-d H:i:s", $today_19_00) . "' AND '" . date("Y-m-d H:i:s", $today_23_00) . "')";
        $result = $conn->query($sql);
        $data_activity = $result->fetch_assoc();
        if (empty($data_activity)){
            $shif = "N" . $team_no;
        }else{
            $shif = $team_no . "N";
        }
    }
    return $shif;
}

$sql = "SELECT id_staff FROM machine_queue WHERE id_machine='" . $_GET["id_mc"] . "' AND queue_number=1";
//echo $sql . "<br>";
$query_queue = $conn->query($sql);
$data_queue = $query_queue->fetch_assoc();
if ($data_queue['id_staff']==null) {
    $sql = "SELECT id_staff, name_first, name_last, id_role as role, id_shif as team FROM staff WHERE id_rfid='" . $_GET['id_rfid'] . "'";
//    echo $sql . "<br>";
    $result = $conn->query($sql);
    $data_staff = $result->fetch_assoc();
    $data_staff['role']=intval($data_staff['role']);

    $shif = find_shif($conn, $data_staff['id_staff'], $data_staff['team']);

//    $sql = "SELECT id_task FROM planning WHERE id_job='" . $_GET['id_job'] . "' AND operation='" . $_GET['operation'] . "'";
    $sql = "SELECT id_task FROM planning WHERE id_task=" . $data_machine_queue['id_task'];
    $result = $conn->query($sql);
    $data_planning_id_task = $result->fetch_assoc();

//    echo $data_staff['role'] . "<br>";
//    if($_GET['activity_type']==1){
    if($data_staff['role']==1){
        $sql = "INSERT INTO activity (id_task, id_machine, id_staff, shif, status_work, time_start) VALUES (";
        $sql = $sql . $data_planning_id_task["id_task"] . ",";
        $sql = $sql . "'" . $_GET["id_mc"] . "',";
        $sql = $sql . "'" . $data_staff["id_staff"] . "',";
        $sql = $sql . "'" . $shif . "',";
        $sql = $sql . "1,";
        $sql = $sql . "CURRENT_TIMESTAMP()";
        $sql = $sql . ")";
//        echo $sql . "<br>";
        $result = $conn->query($sql);

        // UPDATE STAFF ID IN MACHINE QUEUE TABLE
        $sql = "UPDATE machine_queue SET id_staff='" . $data_staff['id_staff'] . "' WHERE id_machine='" . $_GET["id_mc"] . "' AND queue_number=1";
        $result = $conn->query($sql);
//        echo "OK";
//    }elseif ($_GET['activity_type']==2){
    }elseif ($data_staff['role']==2){
        $sql = "INSERT INTO activity_downtime (id_task, id_machine, id_staff, status_work, time_start) VALUES (";
        $sql = $sql . $data_planning_id_task["id_task"] . ",";
        $sql = $sql . "'" . $_GET["id_mc"] . "',";
        $sql = $sql . "'" . $data_staff["id_staff"] . "',";
        $sql = $sql . "1,";
        $sql = $sql . "CURRENT_TIMESTAMP()";
        $sql = $sql . ")";
//        echo $sql . "<br>";
        $result = $conn->query($sql);
        $data_staff["role"]=intval($data_staff["role"]);

        // UPDATE STAFF ID IN MACHINE QUEUE TABLE
        $sql = "UPDATE machine_queue SET id_staff='" . $data_staff['id_staff'] . "' WHERE id_machine='" . $_GET["id_mc"] . "' AND queue_number=1";
        $result = $conn->query($sql);
//        echo "OK";
    }
}else{
    $data_json = json_encode(array("code"=>"025", "message"=>"This machine is currently in occupied by staff ID: " . $data_queue['id_staff']), JSON_UNESCAPED_UNICODE);
//    print_r($data_json);
}

//require "contact.php";
//require 'terminate.php';
