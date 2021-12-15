<?php
require 'establish.php';

$sql = "SELECT id_staff FROM machine_queue WHERE id_machine='" . $_GET["id_mc"] . "' AND queue_number=1";
$query_queue = $conn->query($sql);
$data_queue = $query_queue->fetch_assoc();

if ($data_queue['id_staff']==null){
    $sql = "SELECT id_staff FROM staff WHERE id_rfid='" . $_GET['id_rfid'] . "' AND id_role=2";
    $query_staff = $conn->query($sql);
    $data_staff = $query_staff->fetch_assoc();

    if (!empty($data_staff)){
        $sql = "SELECT id_code_downtime FROM code_downtime WHERE id_code_downtime='" . $_GET["code_downtime"] . "'";
        $query_code_downtime = $conn->query($sql);
        $data_code_downtime = $query_code_downtime->fetch_assoc();

        if(empty($data_code_downtime)) {
            $data_json = json_encode(array("code"=>"015", "message"=>"Downtime code not found"), JSON_UNESCAPED_UNICODE);
            print_r($data_json);

        } else {
            $sql = "UPDATE activity_downtime SET ";
            $sql = $sql . "id_code_downtime='" . $_GET["code_downtime"] . "' ";
            $sql = $sql . "WHERE status_downtime=1 AND ";
            $sql = $sql . "id_machine='" . $_GET["id_mc"] . "' AND ";
            $sql = $sql . "id_task=(";
            $sql = $sql . "SELECT id_task FROM planning WHERE id_job='" . $_GET['id_job'] . "' AND operation='" . $_GET['operation'] . "'";
            $sql = $sql . ")";
            $result = $conn->query($sql);
//    echo $sql;

            // UPDATE STAFF ID IN MACHINE QUEUE TABLE
            $sql = "UPDATE machine_queue SET id_staff='" . $data_staff['id_staff'] . "' WHERE id_machine='" . $_GET["id_mc"] . "' AND queue_number=1";
            $result = $conn->query($sql);

            echo "OK";
        }

    }else{
        $data_json = json_encode(array("code"=>"024", "message"=>"The downtime is not called by the technician card"), JSON_UNESCAPED_UNICODE);
        print_r($data_json);
    }

}else{
    $data_json = json_encode(array("code"=>"023", "message"=>"This machine is currently in occupied by staff ID: " . $data_queue['id_staff']), JSON_UNESCAPED_UNICODE);
    print_r($data_json);
}

require "contact.php";
require 'terminate.php';
