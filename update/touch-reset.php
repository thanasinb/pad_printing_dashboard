<?php
require 'establish.php';

$sql = "SELECT * FROM activity WHERE id_machine = '" . $_GET['id_mc'] . "' AND status_work<3";
$result_activity = $conn->query($sql);
$data_activity = $result_activity->fetch_assoc();

if(!empty($data_activity)){
    $sql="SELECT id_rfid FROM staff WHERE id_staff='" . $data_activity['id_staff'] . "'";
    $result_staff = $conn->query($sql);
    $data_staff = $result_staff->fetch_assoc();

    $sql="SELECT * FROM planning WHERE id_task=" . $data_activity['id_task'];
    $result_planning = $conn->query($sql);
    $data_planning = $result_planning->fetch_assoc();

    header("Location: ./quit_v2-reset.php?" .
    "id_rfid=" . $data_staff['id_rfid'] .
    "&id_job=" . $data_planning['id_job'] .
    "&operation=" . $data_planning['operation'] .
    "&id_mc=" . $data_activity['id_machine'] .
    "&no_send=" . $data_activity['no_send'] .
    "&no_pulse1=" . $data_activity['no_pulse1'] .
    "&no_pulse2=" . $data_activity['no_pulse2'] .
    "&no_pulse3=" . $data_activity['no_pulse3']);
    die();
}

$sql = "SELECT * FROM activity_downtime WHERE id_machine = '" . $_GET['id_mc'] . "' AND status_downtime<3";
$result_downtime = $conn->query($sql);
$data_downtime = $result_downtime->fetch_assoc();

if(!empty($data_downtime)){
    $sql="SELECT id_rfid FROM staff WHERE id_staff='" . $data_downtime['id_staff'] . "'";
    $result_staff = $conn->query($sql);
    $data_staff = $result_staff->fetch_assoc();

    $sql="SELECT * FROM planning WHERE id_task=" . $data_downtime['id_task'];
    $result_planning = $conn->query($sql);
    $data_planning = $result_planning->fetch_assoc();

    header("Location: ./quit_dt-reset.php?" .
        "id_rfid=" . $data_staff['id_rfid'] .
        "&id_job=" . $data_planning['id_job'] .
        "&operation=" . $data_planning['operation'] .
        "&id_mc=" . $data_downtime['id_machine']);
    die();
//    https://bunnam.com/projects/majorette_pp/update/quit_dt.php?id_rfid=0014084240&id_job=6174159&operation=540&id_mc=02-02
}

$sql = "SELECT * FROM activity_rework WHERE id_machine = '" . $_GET['id_mc'] . "' AND status_work<3";
$result_activity_rework = $conn->query($sql);
$data_activity_rework = $result_activity_rework->fetch_assoc();

if(!empty($data_activity_rework)){
    $sql="SELECT id_rfid FROM staff WHERE id_staff='" . $data_activity_rework['id_staff'] . "'";
    $result_staff = $conn->query($sql);
    $data_staff = $result_staff->fetch_assoc();

    $sql="SELECT * FROM planning WHERE id_task=" . $data_activity_rework['id_task'];
    $result_planning = $conn->query($sql);
    $data_planning = $result_planning->fetch_assoc();

    header("Location: ./quit_v2-reset.php?" .
        "id_rfid=" . $data_staff['id_rfid'] .
        "&id_job=" . $data_planning['id_job'] .
        "&operation=" . $data_planning['operation'] .
        "&id_mc=" . $data_activity_rework['id_machine'] .
        "&no_send=" . $data_activity_rework['no_send'] .
        "&no_pulse1=" . $data_activity_rework['no_pulse1'] .
        "&no_pulse2=" . $data_activity_rework['no_pulse2'] .
        "&no_pulse3=" . $data_activity_rework['no_pulse3']);
    die();
}

$sql = "UPDATE machine_queue SET id_staff='' WHERE id_machine='" . $_GET['id_mc'] . "' AND queue_number=1";
$result = $conn->query($sql);

require 'terminate.php';

$data_json = json_encode(array("code"=>"OK", "message"=>"OK"), JSON_UNESCAPED_UNICODE);
print_r($data_json);

if(strcmp($_GET['dashboard'],'1')==0){
    header("Location: ../pp-machine.php");
    die();
}
