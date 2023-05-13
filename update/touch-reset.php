<?php
require 'establish.php';
$sql = "SELECT * FROM machine_queue
         INNER JOIN staff ON machine_queue.id_staff = staff.id_staff 
         WHERE id_machine = '" . $_GET['id_mc'] . "'";
$result_machine_queue = $conn->query($sql);
$data_machine_queue = $result_machine_queue->fetch_assoc();
require 'terminate.php';

if(!empty($data_machine_queue)){

    header("Location: ./quit_v3.php?" .
        "id_mc=" . $data_machine_queue['id_machine'] .
        "&id_rfid=" . $data_machine_queue['id_rfid'] .
        "&id_activity=" . $data_machine_queue['id_activity'] .
        "&activity_type=" . $data_machine_queue['activity_type'] .
        "&no_send=-1" .
        "&no_pulse1=0" .
        "&no_pulse2=0" .
        "&no_pulse3=0" .
        "&multiplier=-1");

    die();
}

$data_json = json_encode(array("code"=>"200", "message"=>"OK"), JSON_UNESCAPED_UNICODE);
print_r($data_json);

if(strcmp($_GET['dashboard'],'1')==0){
    header("Location: ../pp-machine.php");
    die();
}
