<?php

require 'quit_v2.php';

require 'establish.php';

// CHECK DOWNTIME ACTIVITY ALREADY EXISTS IN THE ACTIVITY_DOWNTIME TABLE OR NOT
$sql = "SELECT id_activity_downtime, id_staff FROM activity_downtime WHERE id_machine='" . $_GET["id_mc"] . "' AND status_downtime=1";
$result = $conn->query($sql);
$data_activity_downtime = $result->fetch_assoc();

// INSERT INTO THE TABLE ONLY IF THERE IS NO SUCH ACTIVITY_DOWNTIME (NEW)
if(empty($data_activity_downtime)) {
    $sql = "INSERT INTO activity_downtime (id_task, id_machine, id_staff, id_code_downtime, status_downtime, time_start) VALUES (" . $data_activity['id_task'] . ", 
    '" . $_GET["id_mc"] . "', (SELECT id_staff FROM staff WHERE id_rfid = '" . $_GET['id_rfid'] . "'), '" . $_GET["code_downtime"] . "', 1, CURRENT_TIMESTAMP())";
//    echo $sql;
    $result = $conn->query($sql);

    print_r($data_json);
}else{
    $data_json = json_encode(array("code" => "021", "message" => "Downtime is already registered by staff ID: " . $data_activity_downtime['id_staff']), JSON_UNESCAPED_UNICODE);
    print_r($data_json);
}

require 'terminate.php';
?>