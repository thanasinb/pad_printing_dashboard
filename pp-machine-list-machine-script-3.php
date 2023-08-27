<?php
ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);

require 'update/establish.php';
$sql = "SELECT id_mc FROM machine ORDER BY id_mc ASC";
$result_machine = $conn->query($sql);

while($row_machine = $result_machine->fetch_assoc()){
    echo "<tr class='text-black fw-bold'>";
    $sql = "SELECT * FROM machine_queue WHERE id_machine='" . $row_machine["id_mc"] . "' ORDER BY queue_number ASC";
    $result_machine_queue = $conn->query($sql);

    // IF THERE IS NO QUEUING INFORMATION OF SUCH MACHINE, SHOW EMPTY CELLS
    if (empty($result_machine_queue))
    {
        echo "<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
    }
    else
    {
        // THE QUEUING INFORMATION EXIST, GET THE TASK THAT IS IN THE FIRST QUEUE
        $row_machine_queue = $result_machine_queue->fetch_assoc();
        if (intval($row_machine_queue['queue_number'])==1){

            // FIND THE TASK CORRESPONDING TO THE FIRST QUEUE
            $sql = "SELECT *, divider.divider as divider FROM planning INNER JOIN divider ON planning.op_color=divider.op_color AND planning.op_side=divider.op_side WHERE id_task=" . $row_machine_queue["id_task"];
            $result_planning = $conn->query($sql);

            // SHOW THE TASK THAT IS IN THE FIRST QUEUE
            $row_planning = $result_planning->fetch_assoc();
            $phpdate = strtotime($row_planning["date_due"]);

            $sql = "SELECT SUM(no_pulse1) AS sum_pulse1, SUM(no_pulse2) AS sum_pulse2, SUM(no_pulse3) AS sum_pulse3 ";
            $sql = $sql . "FROM activity WHERE id_task=" . $row_machine_queue["id_task"] . " AND status_work>0 AND status_work<6";
            $result_activity = $conn->query($sql);
            $row_activity = $result_activity->fetch_assoc();

            // CALCULATE THE PERCENT OF THE PROGRESS BAR
            $qty_process = intval($row_activity["sum_pulse2"]);
            $qty_manual = intval($row_activity["sum_pulse3"]);
            $qty_complete = intval($row_planning["qty_comp"]);
            $qty_order = intval($row_planning["qty_order"]);
            $qty_accum = $qty_complete + $qty_process + $qty_manual;
            $percent = round(($qty_accum / $qty_order) * 100,0);

            $run_time_std = number_format((floatval($row_planning['run_time_std'])*3600)-2, 2); // UNIT = SECONDS
            $run_time_open = (($qty_order-$qty_accum)*$run_time_std);

//            echo "<td><i class='status_work fas fa-circle fa-sm me-1 fs-5'></i></td>";
            echo "<td class='status_work text-white text-center'></td>";
            echo "<td class='id_machine'>" . $row_machine["id_mc"] . "</td>";
//            echo "<td class='id_staff'></td>";
            echo "<td class='col-xl-auto item_no'>";
            echo "<button name='id_mc' type='submit' value='" . $row_machine["id_mc"] . "' ";
            echo "data-bs-toggle='modal' data-bs-target='#currentTaskModal' ";
            echo "data-bs-id_machine='" . $row_machine["id_mc"] . "' ";
            echo "data-bs-item_no='" . $row_planning["item_no"] . "' ";
            echo "data-bs-operation='" . $row_planning["operation"] . "' ";
            echo "data-bs-date_due='" . $phpdate . "' ";
            echo "data-bs-qty_per_tray='" . $row_planning["qty_per_pulse2"] . "' ";
            echo "data-bs-qty_accum='" . $qty_accum . "' ";
            echo "data-bs-qty_order='" . $qty_order . "' ";
            echo "data-bs-qty_percent='" . $percent . "' ";
            echo "data-bs-id_task='" . $row_machine_queue["id_task"] . "' ";
            echo "data-bs-id_job='" . $row_planning["id_job"] . "' ";
            echo "data-bs-last_update='" . $row_planning["datetime_update"] . "' ";
            echo "class='btn btn-datatable btn-icon text-black me-2 btn-current-task'>";
            echo "<i class='far fa-edit fs-6'></i></button>" . $row_planning["item_no"] . "</td>";
            echo "<td class='operation'>" . $row_planning['operation'] . "</td>";
            echo "<td class='op_color'>" . $row_planning['op_color'] . "</td>";
            echo "<td class='op_side'>" . $row_planning['op_side'] . "</td>";
            echo "<td class='date_due'>" . date( 'd-m-Y', $phpdate ) . "</td>";
            echo "<td class='qty_per_tray'></td>";
            echo "<td class='qty_accum_order'></td>";
            echo "<td><div class=\"progress\">";
            echo "<div id=\"progress-bar\" class=\"progress-bar\" role=\"progressbar\" style=\"width: " . $percent . "%\" ";
            echo "aria-valuenow=\"" . $percent . "\" aria-valuemin=\"0\" aria-valuemax=\"100\">" . $percent . "%";
            echo "</div></td>";
            echo "<td><span class='run_time'></span></td>";
            echo "<td class='run_time_open'>" . $run_time_open . "</td>";
            echo "<td class='est_date' style='width: 100px'><span></span></td>";
            echo "<td class='est_time' style='width: 80px'><span></span></td>";

            // SHOW THE SECOND QUEUE
            $row_machine_queue = $result_machine_queue->fetch_assoc();

            // IF THERE IS NO SECOND QUEUE
            if (empty($row_machine_queue)){
                echo "<td class='col-xl-auto'>";
                echo "<button name='id_mc' type='submit' value='" . $row_machine["id_mc"] . "' ";
                echo "data-bs-toggle='modal' data-bs-target='#nextTaskModal' ";
                echo "data-bs-id_machine='" . $row_machine["id_mc"] . "' ";
                echo "data-bs-item_no='" . "' ";
                echo "data-bs-operation='" . "' ";
                echo "data-bs-date_due='" . "' ";
                echo "data-bs-qty_per_tray='" . "' ";
                echo "data-bs-qty_accum='" . "' ";
                echo "data-bs-qty_order='" . "' ";
                echo "data-bs-qty_percent='" . "' ";
                echo "data-bs-id_task='" . "' ";
                echo "data-bs-id_job='" . "' ";
                echo "data-bs-last_update='" . "' ";
                echo "class='btn btn-datatable btn-icon text-black me-2 btn-next-task'>";
                echo "<i class='far fa-edit fs-6'></i></button>";
                echo "</td>";
                echo "<td></td>";
            }
            // IF THE SECOND QUEUE EXISTS
            else{
                $sql = "SELECT * FROM planning WHERE id_task=" . $row_machine_queue["id_task"];
                $result_planning = $conn->query($sql);
                $row_planning = $result_planning->fetch_assoc();
                echo "<td class='col-xl-auto'>";
                echo "<button name='id_mc' type='submit' value='" . $row_machine["id_mc"] . "' ";
                echo "data-bs-toggle='modal' data-bs-target='#nextTaskModal' ";
                echo "data-bs-id_machine='" . $row_machine["id_mc"] . "' ";
                echo "data-bs-item_no='" . $row_planning["item_no"] . "' ";
                echo "data-bs-operation='" . $row_planning["operation"] . "' ";
                echo "data-bs-date_due='" . $phpdate . "' ";
                echo "data-bs-qty_per_tray='" . $row_planning["qty_per_pulse2"] . "' ";
                echo "data-bs-qty_accum='" . $qty_accum . "' ";
                echo "data-bs-qty_order='" . $qty_order . "' ";
                echo "data-bs-qty_percent='" . $percent . "' ";
                echo "data-bs-id_task='" . $row_machine_queue["id_task"] . "' ";
                echo "data-bs-id_job='" . $row_planning["id_job"] . "' ";
                echo "data-bs-last_update='" . $row_planning["datetime_update"] . "' ";
                echo "class='btn btn-datatable btn-icon text-black me-2 btn-next-task'>";
                echo "<i class='far fa-edit fs-6'></i></button>";
                echo $row_planning["item_no"];
                echo "</td>";
                echo "<td>" . $row_planning["operation"] . "</td>";
            }
        }

        // IF THERE IS ONLY THE SECOND QUEUE FOR THIS MACHINE
        elseif(intval($row_machine_queue['queue_number'])==2) {
            echo "<td class='status_work text-white'></td>";
            echo "<td class='id_machine'>" . $row_machine["id_mc"] . "</td>";
//            echo "<td class='id_staff'></td>";
            echo "<td class='col-xl-auto'>";
            echo "<button name='id_mc' type='submit' value='" . $row_machine["id_mc"] . "' ";
            echo "data-bs-toggle='modal' data-bs-target='#currentTaskModal' ";
            echo "data-bs-id_machine='" . $row_machine["id_mc"] . "' ";
            echo "data-bs-item_no='" . "' ";
            echo "data-bs-operation='" . "' ";
            echo "data-bs-date_due='" . "' ";
            echo "data-bs-qty_per_tray='" . "' ";
            echo "data-bs-qty_accum='" . "' ";
            echo "data-bs-qty_order='" . "' ";
            echo "data-bs-qty_percent='" . "' ";
            echo "data-bs-id_task='" . "' ";
            echo "data-bs-id_job='" . "' ";
            echo "data-bs-last_update='" . "' ";
            echo "class='btn btn-datatable btn-icon text-black me-2 btn-current-task'>";
            echo "<i class='far fa-edit fs-6'></i></button>";
            echo "</td>";
            echo "<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>";

            $sql = "SELECT * FROM planning WHERE id_task=" . $row_machine_queue["id_task"];
            $result_planning = $conn->query($sql);
            $row_planning = $result_planning->fetch_assoc();
            echo "<td class='col-xl-auto'>";
            echo "<button name='id_mc' type='submit' value='" . $row_machine["id_mc"] . "' ";
            echo "data-bs-toggle='modal' data-bs-target='#nextTaskModal' ";
            echo "data-bs-id_machine='" . $row_machine["id_mc"] . "' ";
            echo "data-bs-item_no='" . $row_planning["item_no"] . "' ";
            echo "data-bs-operation='" . $row_planning["operation"] . "' ";
            echo "data-bs-date_due='" . $phpdate . "' ";
            echo "data-bs-qty_per_tray='" . $row_planning["qty_per_pulse2"] . "' ";
            echo "data-bs-qty_accum='" . $qty_accum . "' ";
            echo "data-bs-qty_order='" . $qty_order . "' ";
            echo "data-bs-qty_percent='" . $percent . "' ";
            echo "data-bs-id_task='" . $row_machine_queue["id_task"] . "' ";
            echo "data-bs-id_job='" . $row_planning["id_job"] . "' ";
            echo "data-bs-last_update='" . $row_planning["datetime_update"] . "' ";
            echo "class='btn btn-datatable btn-icon text-black me-2 btn-next-task'>";
            echo "<i class='far fa-edit fs-6'></i></button>";
            echo $row_planning["item_no"];
            echo "</td>";
            echo "<td>" . $row_planning["operation"] . "</td>";
        }

        // IF THERE IS NO FIRST NOR SECOND QUEUES FOR THIS MACHINE
        else{
            echo "<td class='status_work text-white'></td>";
            echo "<td class='id_machine'>" . $row_machine["id_mc"] . "</td>";
//            echo "<td class='id_staff'></td>";
            echo "<td class='col-xl-auto'>";
            echo "<button name='id_mc' type='submit' value='" . $row_machine["id_mc"] . "' ";
            echo "data-bs-toggle='modal' data-bs-target='#currentTaskModal' ";
            echo "data-bs-id_machine='" . $row_machine["id_mc"] . "' ";
            echo "data-bs-item_no='" . "' ";
            echo "data-bs-operation='" . "' ";
            echo "data-bs-date_due='" . "' ";
            echo "data-bs-qty_per_tray='" . "' ";
            echo "data-bs-qty_accum='" . "' ";
            echo "data-bs-qty_order='" . "' ";
            echo "data-bs-qty_percent='" . "' ";
            echo "data-bs-id_task='" . "' ";
            echo "data-bs-id_job='" . "' ";
            echo "data-bs-last_update='" . "' ";
            echo "class='btn btn-datatable btn-icon text-black me-2 btn-current-task'>";
            echo "<i class='far fa-edit fs-6'></i></button>";
            echo "</td>";
            echo "<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
            echo "<td class='col-xl-auto'>";
            echo "<button name='id_mc' type='submit' value='" . $row_machine["id_mc"] . "' ";
            echo "data-bs-toggle='modal' data-bs-target='#nextTaskModal' ";
            echo "data-bs-id_machine='" . $row_machine["id_mc"] . "' ";
            echo "data-bs-item_no='" . "' ";
            echo "data-bs-operation='" . "' ";
            echo "data-bs-date_due='" . "' ";
            echo "data-bs-qty_per_tray='" . "' ";
            echo "data-bs-qty_accum='" . "' ";
            echo "data-bs-qty_order='" . "' ";
            echo "data-bs-qty_percent='" . "' ";
            echo "data-bs-id_task='" . "' ";
            echo "data-bs-id_job='" . "' ";
            echo "data-bs-last_update='" . "' ";
            echo "class='btn btn-datatable btn-icon text-black me-2 btn-next-task'>";
            echo "<i class='far fa-edit fs-6'></i></button>";
            echo "</td>";
            echo "<td></td>";
        }
    }
    echo "</tr>";
}
require 'update/terminate.php';
?>