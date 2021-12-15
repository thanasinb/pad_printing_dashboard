<?php
require 'establish.php';

$sql = "SELECT id_staff, name_first, name_last, id_role as role FROM staff WHERE id_rfid='" . $_GET["id_rfid"] . "'";
$result = $conn->query($sql);
$data_staff = $result->fetch_assoc();
$data_staff["role"]=intval($data_staff["role"]);

$sql = "SELECT id_task, id_staff FROM machine_queue WHERE id_machine = '" . $_GET["id_mc"] . "' AND queue_number=1";
$result = $conn->query($sql);
$data_machine_queue = $result->fetch_assoc();

// IF THE THE STAFF EXISTS AND THE TASK EXISTS IN THE FIRST QUEUE
if (!empty($data_staff) && !empty($data_machine_queue)){
    if ($data_machine_queue['id_staff']==null){
        //$sql = "SELECT id_task, id_job, item_no, operation, op_color, op_side, op_des AS op_name, qty_order, qty_comp, qty_open FROM planning WHERE id_task=" . $data_machine_queue["id_task"];
        $sql = "SELECT id_task, id_job, item_no, operation, planning.op_color, planning.op_side, op_des AS op_name, qty_order, qty_comp, qty_open, divider as multiplier FROM planning INNER JOIN divider ON (planning.op_color=divider.op_color AND planning.op_side=divider.op_side) WHERE id_task=" . $data_machine_queue["id_task"];
        //echo $sql;
        $result = $conn->query($sql);
        $data_planning = $result->fetch_assoc();

        // IF THE CARD IS OPERATOR CLASS
        if($data_staff["role"]==1){

            // CHECK ACTIVITY ALREADY EXISTS IN THE ACTIVITY TABLE OR NOT
            $sql = "SELECT id_activity, id_staff FROM activity WHERE id_machine='" . $_GET["id_mc"] . "' AND status_work BETWEEN 1 AND 2";
            $result = $conn->query($sql);
            $data_activity = $result->fetch_assoc();

            // INSERT INTO THE TABLE ONLY IF THERE IS NO SUCH ACTIVITY (NEW)
            if(empty($data_activity)){
                $sql = "SELECT id_activity_downtime, id_staff, time_start, CURRENT_TIMESTAMP() AS time_current FROM activity_downtime WHERE id_machine='" . $_GET["id_mc"] . "' AND status_downtime=1";
                $result = $conn->query($sql);
                $data_activity_downtime = $result->fetch_assoc();

                // ADD A NEW ACTIVITY IF THE THERE IS NO ACTIVITY IN THIS MACHINE
                if(empty($data_activity_downtime)){
//                $sql = "INSERT INTO activity (id_task, id_job, operation, id_machine, id_staff, status_work, time_start) VALUES (";
//                $sql = $sql . $data_planning["id_task"] . ",";
//                $sql = $sql . "'" . $data_planning["id_job"] . "',";
//                $sql = $sql . "'" . $data_planning["operation"] . "',";
//                $sql = $sql . "'" . $_GET["id_mc"] . "',";
//                $sql = $sql . "'" . $data_staff["id_staff"] . "',";
//                $sql = $sql . "1,";
//                $sql = $sql . "CURRENT_TIMESTAMP()";
//                $sql = $sql . ")";
//                $result = $conn->query($sql);
//
//                // UPDATE STAFF ID IN MACHINE QUEUE TABLE
//                $sql = "UPDATE machine_queue SET id_staff='" . $data_staff['id_staff'] . "' WHERE id_machine='" . $_GET["id_mc"] . "' AND queue_number=1";
//                $result = $conn->query($sql);

                    require 'activity.php';
                    $data_json = json_encode(array_merge($data_staff, $data_planning), JSON_UNESCAPED_UNICODE);
                }
                else{
                    $data_json = json_encode(array("code"=>"022", "message"=>"This machine is currently in downtime by staff ID: " . $data_activity_downtime['id_staff']), JSON_UNESCAPED_UNICODE);
                }
            }
            // SELECT STAFF INFO IF THE ACTIVITY EXISTS
            else{
                $sql = "SELECT id_staff, name_first, name_last, id_role as role FROM staff WHERE id_staff='" . $data_activity['id_staff'] . "'";
                $result = $conn->query($sql);
                $data_staff = $result->fetch_assoc();
                $data_staff["role"]=intval($data_staff["role"]);
                $data_json = json_encode(array_merge($data_staff, $data_planning), JSON_UNESCAPED_UNICODE);
            }
//        $data_json = json_encode(array_merge($data_staff, $data_planning), JSON_UNESCAPED_UNICODE);
        }

        // IF THE CARD IS TECHNICIAN CLASS
        elseif ($data_staff["role"]==2){

            // CHECK DOWNTIME ACTIVITY ALREADY EXISTS IN THE ACTIVITY_DOWNTIME TABLE OR NOT
            $sql = "SELECT id_activity_downtime, id_staff, time_start, CURRENT_TIMESTAMP() AS time_current FROM activity_downtime WHERE id_machine='" . $_GET["id_mc"] . "' AND status_downtime=1";
            $result = $conn->query($sql);
            $data_activity_downtime = $result->fetch_assoc();
//            echo $sql;

            // INSERT INTO THE TABLE ONLY IF THERE IS NO SUCH ACTIVITY_DOWNTIME (NEW)
            if(empty($data_activity_downtime)) {
                $sql = "INSERT INTO activity_downtime (id_task, id_machine, id_staff, status_downtime, time_start) VALUES (";
                $sql = $sql . $data_planning['id_task'] . ",";
                $sql = $sql . "'" . $_GET['id_mc'] . "',";
                $sql = $sql . "'" . $data_staff['id_staff'] . "',";
                $sql = $sql . "1,";
                $sql = $sql . "CURRENT_TIMESTAMP()";
                $sql = $sql . ")";
                $result = $conn->query($sql);
                $data_json = json_encode(array_merge($data_staff, $data_planning), JSON_UNESCAPED_UNICODE);
//                echo 'hello';
            }
            // IF THE DOWNTIME ACTIVITY EXIST
            else{
                // CHECK THE STAFF WHO RAISED THIS DOWNTIME IS OPERATOR OR TECHNICIAN
                $sql = "SELECT id_staff, name_first, name_last, id_role as role FROM staff WHERE id_staff='" . $data_activity_downtime['id_staff'] . "'";
                $result = $conn->query($sql);
                $data_staff_downtime = $result->fetch_assoc();
                $data_staff_downtime['role']=intval($data_staff_downtime['role']);

//            echo $sql . "<br>";

                // IF THE DOWNTIME ACTIVITY WAS RAISED BY THE OPERATOR, CLOSE THE ACTIVITY AND ADD A NEW ONE USING THE TECHNICIAN INFO
                if ($data_staff_downtime['role']==1){
                    $time_start = strtotime($data_activity_downtime["time_start"]);
                    $time_current = strtotime($data_activity_downtime["time_current"]);
                    $time_total =  gmdate('H:i:s', $time_current-$time_start);

                    $sql = "UPDATE activity_downtime SET ";
                    $sql = $sql . "status_downtime=3,";
                    $sql = $sql . "total_work='" . $time_total . "',";
                    $sql = $sql . "time_close='" . $data_activity_downtime["time_current"] . "' ";
                    $sql = $sql . "WHERE id_activity_downtime=" . $data_activity_downtime["id_activity_downtime"];
                    $query_activity_downtime = $conn->query($sql);

                    $sql = "INSERT INTO activity_downtime (id_task, id_machine, id_staff, status_downtime, time_start) VALUES (";
                    $sql = $sql . $data_planning['id_task'] . ",";
                    $sql = $sql . "'" . $_GET['id_mc'] . "',";
                    $sql = $sql . "'" . $data_staff['id_staff'] . "',";
                    $sql = $sql . "1,";
                    $sql = $sql . "CURRENT_TIMESTAMP()";
                    $sql = $sql . ")";
                    $result = $conn->query($sql);

                    $sql = "UPDATE machine_queue SET id_staff='" . $data_staff['id_staff'] . "' WHERE id_machine='" . $_GET["id_mc"] . "' AND queue_number=1";
                    $result = $conn->query($sql);

                    $data_json = json_encode(array_merge($data_staff, $data_planning), JSON_UNESCAPED_UNICODE);
                }
                // IF THE DOWNTIME ACTIVITY WAS RAISED BY THE TECHNICIAN, SHOW THAT STAFF'S INFO
                else{
                    $data_json = json_encode(array("code"=>"022", "message"=>"This machine is currently in downtime by staff ID: " . $data_activity_downtime['id_staff']), JSON_UNESCAPED_UNICODE);
                }
            }
        }else{
            $data_json = json_encode(array("code"=>"017"), JSON_UNESCAPED_UNICODE);
        }
//    $data_staff["role"]=intval($data_staff["role"]);
//    $data_json = json_encode(array_merge($data_staff,$data_planning), JSON_UNESCAPED_UNICODE);

    }else{
        $data_json = json_encode(array("code"=>"026", "message"=>"This machine is currently in occupied by staff ID: " . $data_machine_queue['id_staff']), JSON_UNESCAPED_UNICODE);
    }
}else{
    $data_json = json_encode(array("code"=>"001", "message"=>"No such job or machine in the planning table"), JSON_UNESCAPED_UNICODE);
}
require "contact.php";
require 'terminate.php';

print_r($data_json);
