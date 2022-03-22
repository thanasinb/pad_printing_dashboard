<?php
require 'establish.php';
require 'find_shif.php';

function add_downtime($conn, $id_task, $id_mc, $id_staff, $shif, $code_downtime){
    $sql = "INSERT INTO activity_downtime (id_task, id_machine, id_staff, shif, id_code_downtime, status_downtime, time_start) VALUES (";
    $sql = $sql . $id_task . ",";
    $sql = $sql . "'" . $id_mc . "',";
    $sql = $sql . "'" . $id_staff . "',";
    $sql = $sql . "'" . $shif . "',";
    $sql = $sql . "'" . $code_downtime . "',";
    $sql = $sql . "1,";
    $sql = $sql . "CURRENT_TIMESTAMP()";
    $sql = $sql . ")";

    $result = $conn->query($sql);

    // UPDATE STAFF ID IN MACHINE QUEUE TABLE
    $sql = "UPDATE machine_queue SET id_staff='" . $id_staff . "' WHERE id_machine='" . $id_mc . "' AND queue_number=1";
    $result = $conn->query($sql);
}

$sql = "SELECT id_staff, id_task FROM machine_queue WHERE id_machine='" . $_GET["id_mc"] . "' AND queue_number=1";
$query_queue = $conn->query($sql);
$data_queue = $query_queue->fetch_assoc();

if ($data_queue['id_staff']==null){
    $sql = "SELECT id_staff, id_shif as team FROM staff WHERE id_rfid='" . $_GET['id_rfid'] . "' AND id_role=2";
    $query_staff = $conn->query($sql);
    $data_staff = $query_staff->fetch_assoc();
    $shif = find_shif($conn, $data_staff['id_staff'], $data_staff['team']);

    if (!empty($data_staff)){
        $sql = "SELECT id_code_downtime FROM code_downtime WHERE id_code_downtime='" . $_GET["code_downtime"] . "'";
        $query_code_downtime = $conn->query($sql);
        $data_code_downtime = $query_code_downtime->fetch_assoc();

        if(empty($data_code_downtime)) {
            $data_json = json_encode(array("code"=>"015", "message"=>"Downtime code not found"), JSON_UNESCAPED_UNICODE);
            print_r($data_json);

        } else {
//            $sql = "INSERT INTO activity_downtime (id_task, id_machine, id_staff, shif, id_code_downtime, status_downtime, time_start) VALUES (";
//            $sql = $sql . $data_queue['id_task'] . ",";
//            $sql = $sql . "'" . $_GET['id_mc'] . "',";
//            $sql = $sql . "'" . $data_staff['id_staff'] . "',";
//            $sql = $sql . "'" . $shif . "',";
//            $sql = $sql . "'" . $_GET["code_downtime"] . "',";
//            $sql = $sql . "1,";
//            $sql = $sql . "CURRENT_TIMESTAMP()";
//            $sql = $sql . ")";
//
//            $result = $conn->query($sql);
//
//            // UPDATE STAFF ID IN MACHINE QUEUE TABLE
//            $sql = "UPDATE machine_queue SET id_staff='" . $data_staff['id_staff'] . "' WHERE id_machine='" . $_GET["id_mc"] . "' AND queue_number=1";
//            $result = $conn->query($sql);

            add_downtime($conn, $data_queue['id_task'], $_GET['id_mc'], $data_staff['id_staff'], $shif, $_GET["code_downtime"]);
            echo "OK";
        }

    }else{
        $data_json = json_encode(array("code"=>"024", "message"=>"The downtime is not called by the technician card"), JSON_UNESCAPED_UNICODE);
        print_r($data_json);
    }

}else{
    $sql = "SELECT id_rfid FROM staff INNER JOIN activity_downtime ON (staff.id_staff = activity_downtime.id_staff AND status_downtime=1) 
            WHERE staff.id_staff='" . $data_queue['id_staff'] . "' AND id_role=1";
    $result = $conn->query($sql);
    $data_operator = $result->fetch_assoc();

    if(empty($data_operator)){
        $data_json = json_encode(array("code"=>"023", "message"=>"This machine is currently in occupied by staff ID: " . $data_queue['id_staff']), JSON_UNESCAPED_UNICODE);
        print_r($data_json);
    }
    else{
        $sql = "SELECT id_staff, id_shif as team FROM staff WHERE id_rfid='" . $_GET['id_rfid'] . "' AND id_role=2";
        $query_staff = $conn->query($sql);
        $data_staff = $query_staff->fetch_assoc();
        $shif = find_shif($conn, $data_staff['id_staff'], $data_staff['team']);

        if (!empty($data_staff)) {
            $sql = "SELECT id_code_downtime FROM code_downtime WHERE id_code_downtime='" . $_GET["code_downtime"] . "'";
            $query_code_downtime = $conn->query($sql);
            $data_code_downtime = $query_code_downtime->fetch_assoc();

            if (empty($data_code_downtime)) {
                $data_json = json_encode(array("code" => "015", "message" => "Downtime code not found"), JSON_UNESCAPED_UNICODE);
                print_r($data_json);

            } else {
                $get_rfid = $_GET['id_rfid'];
                $_GET['id_rfid'] = $data_operator['id_rfid'];
                require 'terminate.php';
                require 'quit_dt.php';
                require 'establish.php';
                $_GET['id_rfid'] = $get_rfid;

                add_downtime($conn, $data_queue['id_task'], $_GET['id_mc'], $data_staff['id_staff'], $shif, $_GET["code_downtime"]);
//                echo "OK";
            }
        }else{
            $data_json = json_encode(array("code"=>"024", "message"=>"The downtime is not called by the technician card"), JSON_UNESCAPED_UNICODE);
            print_r($data_json);
        }
    }

}

require "contact.php";
require 'terminate.php';
