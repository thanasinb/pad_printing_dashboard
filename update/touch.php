<?php
require 'establish.php';
require 'find_qty_shif.php';

function getPlanning($conn, $id_task) {
    $sql = "SELECT id_task, id_job, item_no, operation, planning.op_color, planning.op_side, op_des AS op_name, qty_order, qty_comp, qty_open, divider as multiplier 
            FROM planning INNER JOIN divider ON (planning.op_color=divider.op_color AND planning.op_side=divider.op_side) WHERE id_task=" . $id_task;
    $result = $conn->query($sql);
    $data_planning = $result->fetch_assoc();
    $data_planning['item_no'] = substr($data_planning['item_no'], 0, -3);
    return $data_planning;
}

$sql = "SELECT id_staff, name_first, name_last, id_role as role FROM staff WHERE id_rfid='" . $_GET["id_rfid"] . "'";
$result = $conn->query($sql);
$data_staff = $result->fetch_assoc();
$data_staff["role"]=intval($data_staff["role"]);

$sql = "SELECT id_task, id_staff FROM machine_queue WHERE id_machine = '" . $_GET["id_mc"] . "' AND queue_number=1";
$result = $conn->query($sql);
$data_machine_queue = $result->fetch_assoc();

// IF NO EXISTING STAFF ID IN THE QUEUE, THE NEW STAFF CAN PROCEED, REPLY THE STAFF AND TASK INFO
if($data_machine_queue['id_staff'] == null){
    $data_planning = getPlanning($conn, $data_machine_queue['id_task']);
    $data_activity = find_qty_shif($conn, $data_staff['id_staff'], $data_machine_queue['id_task']);
    $data_json = json_encode(array_merge($data_staff, $data_planning, $data_activity), JSON_UNESCAPED_UNICODE);
}

// OTHERWISE, CHECK IF
// 1. THE EXISTING STAFF IS OPERATOR
// 2. THE MACHINE IS IN DOWNTIME
// 3. THE TOUCHED CARD IS TECHNICIAN
// THEN, THE EXISTING DOWNTIME ACTIVITY IS OVERRIDED BY THE TECHNICIAN
else{
    // GET THE STAFF ID FROM THE ACTIVITY_DOWNTIME TABLE
    $sql = "SELECT id_staff FROM activity_downtime WHERE id_machine = '" . $_GET["id_mc"] . "' AND status_downtime=1";
    $result = $conn->query($sql);
    $data_activity_downtime = $result->fetch_assoc();

    // IF THE STAFF ID EXISTS, GET THE EXISTING STAFF ROLE
    if (!empty($data_activity_downtime["id_staff"])){
        $sql = "SELECT id_role as role FROM staff WHERE id_staff='" . $data_machine_queue['id_staff']  . "'";
        $result = $conn->query($sql);
        $data_staff_dt = $result->fetch_assoc();
        $data_staff_dt["role"]=intval($data_staff_dt["role"]);

        // IF THE STAFF WHO RAISES THIS DOWNTIME IS AN OPERTOR (ROLE=1) AND THE TOUCHED CARD IS TECHNICIAN (ROLE=2),
        // CLOSE THE CURRENT DOWNTIME RETURN STAFF AND TASK INFO AS USUAL
        if(($data_staff["role"]==2) and ($data_staff_dt["role"]==1)){
            $data_planning = getPlanning($conn, $data_machine_queue['id_task']);
            $data_json = json_encode(array_merge($data_staff, $data_planning), JSON_UNESCAPED_UNICODE);
        }
        // OTHERWISE, RETURN AN ERROR MESSAGE
        else{
            $data_json = json_encode(array("code"=>"027", "message"=>"This machine is currently in occupied by staff ID: " . $data_machine_queue['id_staff']), JSON_UNESCAPED_UNICODE);
        }
    }
    // IF THE STAFF ID DOES NOT EXIST, THIS MACHINE IS NOT IN DOWNTIME AND IS IN OPERATION, RETURN AN ERROR MESSAGE
    else{
        $data_json = json_encode(array("code"=>"026", "message"=>"This machine is currently in occupied by staff ID: " . $data_machine_queue['id_staff']), JSON_UNESCAPED_UNICODE);
    }
}

require "contact.php";
require 'terminate.php';

print_r($data_json);
