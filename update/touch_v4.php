<?php
require 'establish.php';

$sql = "SELECT id_staff,name_first,name_last, id_role as role FROM staff WHERE id_rfid='" . $_GET["id_rfid"] . "'";
$result = $conn->query($sql);
$data_staff = $result->fetch_assoc();

$sql = "SELECT id_task FROM machine_queue WHERE id_machine = '" . $_GET["id_mc"] . "' AND queue_number=1";
$result = $conn->query($sql);
$data_machine_queue = $result->fetch_assoc();

if (!empty($data_staff) && !empty($data_machine_queue)) {

    // CHECK ACTIVITY ALREADY EXISTS IN THE ACTIVITY TABLE OR NOT
    $sql = "SELECT id_activity, id_staff FROM activity WHERE id_machine='" . $_GET["id_mc"] . "' AND status_work BETWEEN 1 AND 2";
    $result = $conn->query($sql);
    $data_activity = $result->fetch_assoc();

    // CHECK DOWNTIME ACTIVITY ALREADY EXISTS IN THE ACTIVITY_DOWNTIME TABLE OR NOT
    $sql = "SELECT id_activity_downtime, id_staff FROM activity_downtime WHERE id_machine='" . $_GET["id_mc"] . "' AND status_downtime=1";
    $result = $conn->query($sql);
    $data_activity_downtime = $result->fetch_assoc();

    if (!empty($data_activity)) {
        $data_json = json_encode(array("code" => "018", "message" => "The following OPERATOR: " . $data_activity['id_staff'] . " is occupying machine: " . $_GET["id_mc"]), JSON_UNESCAPED_UNICODE);
    } elseif (!empty($data_activity_downtime)) {
        $data_json = json_encode(array("code" => "019", "message" => "The following TECHNICIAN: " . $data_activity_downtime['id_staff'] . " is occupying machine: " . $_GET["id_mc"]), JSON_UNESCAPED_UNICODE);
    }
    // REGISTER THE NEW ACTIVITY IF THERE'S NO EXISTING ACTIVITY IN ACTIVITY AND ACTIVITY_DOWNTIME TABLES
    elseif (empty($data_activity) && empty($data_activity_downtime)) {

        //$sql = "SELECT id_task, id_job, item_no, operation, op_color, op_side, op_des AS op_name, qty_order, qty_comp, qty_open FROM planning WHERE id_task=" . $data_machine_queue["id_task"];
        $sql = "SELECT id_task, id_job, item_no, operation, planning.op_color, planning.op_side, op_des AS op_name, qty_order, qty_comp, qty_open, divider as multiplier FROM planning INNER JOIN divider ON (planning.op_color=divider.op_color AND planning.op_side=divider.op_side) WHERE id_task=" . $data_machine_queue["id_task"];
        //echo $sql;
        $result = $conn->query($sql);
        $data_planning = $result->fetch_assoc();

        $data_staff["role"] = intval($data_staff["role"]);

        // INSERT THE NEW ACTIVITY IF THE CARD IS OPERATOR CLASS
        if ($data_staff["role"] == 1) {
            $sql = "INSERT INTO activity (id_task, id_job, operation, id_machine, id_staff, status_work, time_start) VALUES (";
            $sql = $sql . $data_planning["id_task"] . ",";
            $sql = $sql . "'" . $data_planning["id_job"] . "',";
            $sql = $sql . "'" . $data_planning["operation"] . "',";
            $sql = $sql . "'" . $_GET["id_mc"] . "',";
            $sql = $sql . "'" . $data_staff["id_staff"] . "',";
            $sql = $sql . "1,";
            $sql = $sql . "CURRENT_TIMESTAMP()";
            $sql = $sql . ")";
            $result = $conn->query($sql);

            // UPDATE STAFF ID IN MACHINE QUEUE TABLE
            $sql = "UPDATE machine_queue SET id_staff='" . $data_staff['id_staff'] . "' WHERE id_machine='" . $_GET["id_mc"] . "' AND queue_number=1";
            $result = $conn->query($sql);
        } // INSERT THE NEW ACTIVITY_DOWNTIME IF THE CARD IS TECHNICIAN CLASS
        elseif ($data_staff["role"] == 2) {
            $sql = "INSERT INTO activity_downtime (id_task, id_machine, id_staff, status_downtime, time_start) VALUES (";
            $sql = $sql . $data_planning["id_task"] . ",";
            $sql = $sql . "'" . $_GET["id_mc"] . "',";
            $sql = $sql . "'" . $data_staff["id_staff"] . "',";
            $sql = $sql . "1,";
            $sql = $sql . "CURRENT_TIMESTAMP()";
            $sql = $sql . ")";
            $result = $conn->query($sql);
        }
        $data_json = json_encode(array_merge($data_staff, $data_planning), JSON_UNESCAPED_UNICODE);
    }
    else {
        $data_json = json_encode(array("code" => "001", "message" => "The staff does not exist OR there is no task assigned to the machine"), JSON_UNESCAPED_UNICODE);
    }
}

require "contact.php";
require 'terminate.php';
print_r($data_json);
