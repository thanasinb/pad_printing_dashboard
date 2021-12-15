<?php
ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);

require 'update/establish.php';
$sql = "SELECT id_mc FROM machine ORDER BY id_mc ASC";
$result_machine = $conn->query($sql);

$num_datetime = 0;

while($row_machine = $result_machine->fetch_assoc()){
    echo "<tr class='text-black fw-bold'>";
    $sql = "SELECT * FROM machine_queue WHERE id_machine='" . $row_machine["id_mc"] . "' ORDER BY queue_number ASC";
    $result_machine_queue = $conn->query($sql);

    // IF THERE IS NO QUEUING INFORMATION OF SUCH MACHINE, SHOW EMPTY CELLS
    if (empty($result_machine_queue))
    {
        echo "<td>" . "</td>";
        echo "<td>" . "</td>";
        echo "<td>" . "</td>";
        echo "<td>" . "</td>";
        echo "<td>" . "</td>";
        echo "<td>" . "</td>";
        echo "<td>" . "</td>";
        echo "<td>" . "</td>";
    }
    else
    {
        // THE QUEUING INFORMATION EXIST, GET THE TASK THAT IS IN THE FIRST QUEUE
        $row_machine_queue = $result_machine_queue->fetch_assoc();
        if (intval($row_machine_queue['queue_number'])==1){

            // FIND THE TASK CORRESPONDING TO THE FIRST QUEUE
            $sql = "SELECT * FROM planning WHERE id_task=" . $row_machine_queue["id_task"];
            $result_planning = $conn->query($sql);

            // SHOW THE TASK THAT IS IN THE FIRST QUEUE
            $row_planning = $result_planning->fetch_assoc();
            $phpdate = strtotime($row_planning["date_due"]);

            // SELECT THE PROCESSING QUANTITY (no_pulse1, no_pulse2, no_pulse3) BY TASK_ID
            $sql = "SELECT SUM(no_pulse1) AS sum_pulse1, SUM(no_pulse2) AS sum_pulse2, SUM(no_pulse3) AS sum_pulse3 ";
            $sql = $sql . "FROM activity WHERE id_task=" . $row_machine_queue["id_task"] . " AND status_work>0 AND status_work<3";
            $result_activity = $conn->query($sql);
            $row_activity = $result_activity->fetch_assoc();

            // CALCULATE THE PERCENT OF THE PROGRESS BAR
            $qty_process = intval($row_activity["sum_pulse1"]);
            $qty_complete = intval($row_planning["qty_comp"]);
            $qty_order = intval($row_planning["qty_order"]);
            $qty_accum = $qty_complete + $qty_process;
            $percent = round(($qty_accum / $qty_order) * 100,0);

            // SELECT WORKING STATUS OF THE CURRENT TASK TO SHOW AS COLOUR
            $sql = "SELECT id_activity, status_work, run_time_actual ";
            $sql = $sql . "FROM activity WHERE id_task=" . $row_machine_queue["id_task"] . " AND id_machine='" . $row_machine["id_mc"];
            $sql = $sql . "' ORDER BY id_activity DESC LIMIT 1";

            $result_activity = $conn->query($sql);
            $row_activity = $result_activity->fetch_assoc();
            $status_work = intval($row_activity["status_work"]);
            if (intval($row_planning['task_complete'])==0){
                if ($status_work==0 or $status_work==3){
                    echo "<td>" . "<i class='status_work fas fa-circle fa-sm me-1 text-blue fs-6'></i>" . "</td>";
                }elseif ($status_work==1){
                    echo "<td>" . "<i class='status_work fas fa-circle fa-sm me-1 text-green fs-6'></i>" . "</td>";
                }elseif ($status_work==2){
                    echo "<td>" . "<i class='status_work fas fa-circle fa-sm me-1 text-yellow fs-6'></i>" . "</td>";
                }
            }else{
                echo "<td>" . "<span class='me-1 text-black'>Complete!</span>" . "</td>";
            }
            echo "<td class='id_machine'>" . $row_machine["id_mc"] . "</td>";

            echo "<td class='col-xl-auto'>";
//            echo "<button name='id_mc' type='submit' value='" . $row_machine["id_mc"] . "' ";
//            echo "data-bs-toggle='modal' data-bs-target='#currentTaskModal' ";
//            echo "data-bs-id_machine='" . $row_machine["id_mc"] . "' ";
//            echo "data-bs-item_no='" . $row_planning["item_no"] . "' ";
//            echo "data-bs-operation='" . $row_planning["operation"] . "' ";
//            echo "data-bs-date_due='" . $phpdate . "' ";
//            echo "data-bs-qty_accum='" . $qty_accum . "' ";
//            echo "data-bs-qty_order='" . $qty_order . "' ";
//            echo "data-bs-qty_percent='" . $percent . "' ";
//            echo "data-bs-id_task='" . $row_machine_queue["id_task"] . "' ";
//            echo "data-bs-id_job='" . $row_planning["id_job"] . "' ";
//            echo "data-bs-last_update='" . $row_planning["datetime_update"] . "' ";
//            echo "class='btn btn-datatable btn-icon text-black me-2 btn-current-task'>";
//            echo "<i class='far fa-edit fs-6'></i></button>";
            echo $row_planning["item_no"];
            echo "</td>";
            echo "<td>" . $row_planning["operation"] . "</td>";
            echo "<td style='width: 160px'>";
            echo "<div class='form-group'>";
            echo "<div class='input-group date datetimepicker_date' id='datetimepicker_date_" . $num_datetime . "' data-target-input='nearest'>";
            echo "<input id='input_date_" . $num_datetime . "' class='text-black fw-bold form-control-solid col-10 datetimepicker-input me-1' data-target='#datetimepicker_date_" . $num_datetime . "'/>";
            echo "<div class='input-group-append' data-target='#datetimepicker_date_" . $num_datetime . "' data-toggle='datetimepicker'>";
            echo "<i class='far fa-calendar-alt fs-6'></i>";
            echo "</div></div></div>";
            echo "</td>";
            echo "<td style='width: 130px'>";
            echo "<div class='form-group'>";
            echo "<div class='input-group date datetimepicker_time' id='datetimepicker_time_" . $num_datetime . "' data-target-input='nearest'>";
            echo "<input id='input_time_" . $num_datetime . "' type='text' class='text-black fw-bold form-control-solid col-9 datetimepicker-input me-1' data-target='#datetimepicker_time_" . $num_datetime . "' />";
            echo "<div class='input-group-append' data-target='#datetimepicker_time_" . $num_datetime . "' data-toggle='datetimepicker'>";
            echo "<i class='far fa-clock fs-6'></i>";
            echo "</div></div></div>";
            echo "</td>";
            $num_datetime++;

            echo "<td>" . date( 'd-m-Y', $phpdate ) . "</td>";
            echo "<td style='width: 20px' class='multiplier'>";
            echo "<div class='form-group'>";
            echo "<div class='input-group'>";
            echo "<input class='text-black fw-bold form-control-solid col-9 me-1' type='text' value='" . $row_planning['multiplier'] . "' disabled>";
            echo "</div>";
            echo "</div>";
            echo "</td>";

            echo "<td class='qty_accum_order'>" . number_format($qty_accum) . " / " . number_format($qty_order) . "</td>";
            echo "<td>";
            echo "<div class=\"progress\">";
            echo "<div id=\"progress-bar\" class=\"progress-bar\" role=\"progressbar\" style=\"width: " . $percent . "%\" ";
            echo "aria-valuenow=\"" . $percent . "\" aria-valuemin=\"0\" aria-valuemax=\"100\">" . $percent . "%";
            echo "</div>";
            echo "</td>";
            echo "<td>";
//            $run_time_std = (floatval($row_planning['run_time_std'])*3600.0)-2.0;
//            $run_time_actual = floatval($row_activity['run_time_actual']);
//            if ($run_time_actual>$run_time_std){
//                echo "<span class='run_time blink_me'>";
//            }else{
                echo "<span class='run_time'>";
//            }
//            echo number_format($run_time_actual,2) . " / " . number_format(round($run_time_std, 2),2);
            echo "</span>";
            echo "</td>";
            echo "<td class='run_time_open'>" . number_format($row_planning['run_open_total'],3) . "</td>";

            // SHOW THE SECOND QUEUE
            $row_machine_queue = $result_machine_queue->fetch_assoc();

            // IF THERE IS NO SECOND QUEUE
            if (empty($row_machine_queue)){
                echo "<td class='col-xl-auto'>";
//                echo "<button name='id_mc' type='submit' value='" . $row_machine["id_mc"] . "' ";
//                echo "data-bs-toggle='modal' data-bs-target='#nextTaskModal' ";
//                echo "data-bs-id_machine='" . $row_machine["id_mc"] . "' ";
//                echo "data-bs-item_no='" . "' ";
//                echo "data-bs-operation='" . "' ";
//                echo "data-bs-date_due='" . "' ";
//                echo "data-bs-qty_accum='" . "' ";
//                echo "data-bs-qty_order='" . "' ";
//                echo "data-bs-qty_percent='" . "' ";
//                echo "data-bs-id_task='" . "' ";
//                echo "data-bs-id_job='" . "' ";
//                echo "data-bs-last_update='" . "' ";
//                echo "class='btn btn-datatable btn-icon text-black me-2 btn-next-task'>";
//                echo "<i class='far fa-edit fs-6'></i></button>";
                echo "</td>";
                echo "<td>" . "</td>";
            }
            // IF THE SECOND QUEUE EXISTS
            else{
                $sql = "SELECT * FROM planning WHERE id_task=" . $row_machine_queue["id_task"];
                $result_planning = $conn->query($sql);
                $row_planning = $result_planning->fetch_assoc();
                echo "<td class='col-xl-auto'>";
//                echo "<button name='id_mc' type='submit' value='" . $row_machine["id_mc"] . "' ";
//                echo "data-bs-toggle='modal' data-bs-target='#nextTaskModal' ";
//                echo "data-bs-id_machine='" . $row_machine["id_mc"] . "' ";
//                echo "data-bs-item_no='" . $row_planning["item_no"] . "' ";
//                echo "data-bs-operation='" . $row_planning["operation"] . "' ";
//                echo "data-bs-date_due='" . $phpdate . "' ";
//                echo "data-bs-qty_accum='" . $qty_accum . "' ";
//                echo "data-bs-qty_order='" . $qty_order . "' ";
//                echo "data-bs-qty_percent='" . $percent . "' ";
//                echo "data-bs-id_task='" . $row_machine_queue["id_task"] . "' ";
//                echo "data-bs-id_job='" . $row_planning["id_job"] . "' ";
//                echo "data-bs-last_update='" . $row_planning["datetime_update"] . "' ";
//                echo "class='btn btn-datatable btn-icon text-black me-2 btn-next-task'>";
//                echo "<i class='far fa-edit fs-6'></i></button>";
                echo $row_planning["item_no"];
                echo "</td>";
                echo "<td>" . $row_planning["operation"] . "</td>";
            }
        }

        // IF THERE IS ONLY THE SECOND QUEUE FOR THIS MACHINE
        elseif(intval($row_machine_queue['queue_number'])==2) {
            echo "<td>" . "</td>";
            echo "<td class='id_machine'>" . $row_machine["id_mc"] . "</td>";
            echo "<td class='col-xl-auto'>";
//            echo "<button name='id_mc' type='submit' value='" . $row_machine["id_mc"] . "' ";
//            echo "data-bs-toggle='modal' data-bs-target='#currentTaskModal' ";
//            echo "data-bs-id_machine='" . $row_machine["id_mc"] . "' ";
//            echo "data-bs-item_no='" . "' ";
//            echo "data-bs-operation='" . "' ";
//            echo "data-bs-date_due='" . "' ";
//            echo "data-bs-qty_accum='" . "' ";
//            echo "data-bs-qty_order='" . "' ";
//            echo "data-bs-qty_percent='" . "' ";
//            echo "data-bs-id_task='" . "' ";
//            echo "data-bs-id_job='" . "' ";
//            echo "data-bs-last_update='" . "' ";
//            echo "class='btn btn-datatable btn-icon text-black me-2 btn-current-task'>";
//            echo "<i class='far fa-edit fs-6'></i></button>";
            echo "</td>";
            echo "<td>" . "</td>";
            echo "<td>" . "</td>";
            echo "<td>" . "</td>";
            echo "<td>" . "</td>";
            echo "<td>" . "</td>";
            echo "<td>" . "</td>";
            echo "<td>" . "</td>";
            echo "<td>" . "</td>";
            echo "<td>" . "</td>";

            $sql = "SELECT * FROM planning WHERE id_task=" . $row_machine_queue["id_task"];
            $result_planning = $conn->query($sql);
            $row_planning = $result_planning->fetch_assoc();
            echo "<td class='col-xl-auto'>";
//            echo "<button name='id_mc' type='submit' value='" . $row_machine["id_mc"] . "' ";
//            echo "data-bs-toggle='modal' data-bs-target='#nextTaskModal' ";
//            echo "data-bs-id_machine='" . $row_machine["id_mc"] . "' ";
//            echo "data-bs-item_no='" . $row_planning["item_no"] . "' ";
//            echo "data-bs-operation='" . $row_planning["operation"] . "' ";
//            echo "data-bs-date_due='" . $phpdate . "' ";
//            echo "data-bs-qty_accum='" . $qty_accum . "' ";
//            echo "data-bs-qty_order='" . $qty_order . "' ";
//            echo "data-bs-qty_percent='" . $percent . "' ";
//            echo "data-bs-id_task='" . $row_machine_queue["id_task"] . "' ";
//            echo "data-bs-id_job='" . $row_planning["id_job"] . "' ";
//            echo "data-bs-last_update='" . $row_planning["datetime_update"] . "' ";
//            echo "class='btn btn-datatable btn-icon text-black me-2 btn-next-task'>";
//            echo "<i class='far fa-edit fs-6'></i></button>";
            echo $row_planning["item_no"];
            echo "</td>";
            echo "<td>" . $row_planning["operation"] . "</td>";
        }

        // IF THERE IS NO FIRST NOR SECOND QUEUES FOR THIS MACHINE
        else{
            echo "<td>" . "</td>";
            echo "<td class='id_machine'>" . $row_machine["id_mc"] . "</td>";
            echo "<td class='col-xl-auto'>";
//            echo "<button name='id_mc' type='submit' value='" . $row_machine["id_mc"] . "' ";
//            echo "data-bs-toggle='modal' data-bs-target='#currentTaskModal' ";
//            echo "data-bs-id_machine='" . $row_machine["id_mc"] . "' ";
//            echo "data-bs-item_no='" . "' ";
//            echo "data-bs-operation='" . "' ";
//            echo "data-bs-date_due='" . "' ";
//            echo "data-bs-qty_accum='" . "' ";
//            echo "data-bs-qty_order='" . "' ";
//            echo "data-bs-qty_percent='" . "' ";
//            echo "data-bs-id_task='" . "' ";
//            echo "data-bs-id_job='" . "' ";
//            echo "data-bs-last_update='" . "' ";
//            echo "class='btn btn-datatable btn-icon text-black me-2 btn-current-task'>";
//            echo "<i class='far fa-edit fs-6'></i></button>";
            echo "</td>";
            echo "<td>" . "</td>";
            echo "<td>" . "</td>";
            echo "<td>" . "</td>";
            echo "<td>" . "</td>";
            echo "<td>" . "</td>";
            echo "<td>" . "</td>";
            echo "<td>" . "</td>";
            echo "<td>" . "</td>";
            echo "<td>" . "</td>";
            echo "<td class='col-xl-auto'>";
//            echo "<button name='id_mc' type='submit' value='" . $row_machine["id_mc"] . "' ";
//            echo "data-bs-toggle='modal' data-bs-target='#nextTaskModal' ";
//            echo "data-bs-id_machine='" . $row_machine["id_mc"] . "' ";
//            echo "data-bs-item_no='" . "' ";
//            echo "data-bs-operation='" . "' ";
//            echo "data-bs-date_due='" . "' ";
//            echo "data-bs-qty_accum='" . "' ";
//            echo "data-bs-qty_order='" . "' ";
//            echo "data-bs-qty_percent='" . "' ";
//            echo "data-bs-id_task='" . "' ";
//            echo "data-bs-id_job='" . "' ";
//            echo "data-bs-last_update='" . "' ";
//            echo "class='btn btn-datatable btn-icon text-black me-2 btn-next-task'>";
//            echo "<i class='far fa-edit fs-6'></i></button>";
            echo "</td>";
            echo "<td>" . "</td>";
        }
    }
    echo "</tr>";
}
require 'update/terminate.php';
?>
<div class="modal fade" id="currentTaskModal" tabindex="-1" aria-labelledby="currentTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="currentTaskModalLabel">Current task for machine: </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span id='modal_id_machine'></span><br>
                <span id='modal_item_no'></span><br>
                <span id='modal_operation'></span><br>
                <span id='modal_date_due'></span><br>
                <span id='modal_qty_accum'></span><br>
                <span id='modal_qty_order'></span><br>
                <span id='modal_qty_percent'></span><br>
                <span id='modal_id_task'></span><br>
                <span id='modal_id_job'></span><br>
                <span id='modal_last_update'></span><br>
                <br>
<!--                <h5>Action: </h5>-->
<!--                <form id="form_modal_current_task" method="post">-->
<!--                    <input type="hidden" id="selected_radio" name="selected_radio" value="0">-->
<!--                    <input type="hidden" id="hidden_id_job" name="id_job" value="0">-->
<!--                    <input type="hidden" id="hidden_id_machine" name="id_mc" value="0">-->
<!--                    <input type="hidden" id="hidden_item_no" name="hidden_item_no" value="0">-->
<!--                    <input type="hidden" id="hidden_operation" name="operation" value="0">-->
<!--                    <input type="hidden" id="hidden_current_task" name="is_current_task" value="1">-->
<!--                    <div class="mb-3">-->
<!--                        <div class="form-check">-->
<!--                            <input class="form-check-input radioCurrentTask" type="radio" name="radioCurrentTask" id="radioChangeOp" value="1">-->
<!--                            <label class="form-check-label" for="radioChangeOp">-->
<!--                                Change operation-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="form-check">-->
<!--                            <input class="form-check-input radioCurrentTask" type="radio" name="radioCurrentTask" id="radioForceStop" value="2">-->
<!--                            <label class="form-check-label" for="radioForceStop">-->
<!--                                Force stop-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="form-check">-->
<!--                            <input class="form-check-input radioCurrentTask" type="radio" name="radioCurrentTask" id="radioComplete" value="3">-->
<!--                            <label class="form-check-label" for="radioComplete">-->
<!--                                Mark as complete-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="form-check">-->
<!--                            <input class="form-check-input radioCurrentTask" type="radio" name="radioCurrentTask" id="radioRemove" value="4">-->
<!--                            <label class="form-check-label" for="radioRemove">-->
<!--                                Remove this task-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="form-check">-->
<!--                            <input class="form-check-input radioCurrentTask" type="radio" name="radioCurrentTask" id="radioNextQueue" value="5">-->
<!--                            <label class="form-check-label" for="radioNextQueue">-->
<!--                                Feed task from next queue-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="form-check">-->
<!--                            <input class="form-check-input radioCurrentTask" type="radio" name="radioCurrentTask" id="radioNewTask" value="6">-->
<!--                            <label class="form-check-label" for="radioNewTask">-->
<!--                                Select a new task-->
<!--                            </label>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </form>-->
            </div>
            <div class="modal-footer">
                <button type="button" id="modal_button_go" type='submit' class="btn btn-primary" disabled>Go!</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="nextTaskModal" tabindex="-1" aria-labelledby="nextTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nextTaskModalLabel">Next task for machine: </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span id='modal_next_id_machine'></span><br>
                <span id='modal_next_item_no'></span><br>
                <span id='modal_next_operation'></span><br>
                <span id='modal_next_date_due'></span><br>
                <span id='modal_next_qty_accum'></span><br>
                <span id='modal_next_qty_order'></span><br>
                <span id='modal_next_qty_percent'></span><br>
                <span id='modal_next_id_task'></span><br>
                <span id='modal_next_id_job'></span><br>
                <span id='modal_next_last_update'></span><br>
                <br>
<!--                <h5>Action: </h5>-->
<!--                <form id="form_modal_next_task" method="post">-->
<!--                    <input type="hidden" id="next_selected_radio" name="selected_radio" value="0">-->
<!--                    <input type="hidden" id="next_hidden_id_job" name="id_job" value="0">-->
<!--                    <input type="hidden" id="next_hidden_id_machine" name="id_mc" value="0">-->
<!--                    <input type="hidden" id="next_hidden_item_no" name="hidden_item_no" value="0">-->
<!--                    <input type="hidden" id="next_hidden_operation" name="operation" value="0">-->
<!--                    <input type="hidden" id="next_hidden_current_task" name="is_current_task" value="0">-->
<!--                    <div class="mb-3">-->
<!--                        <div class="form-check">-->
<!--                            <input class="form-check-input radioNextTask" type="radio" name="radioNextTask" id="radioNextChangeOp" value="1">-->
<!--                            <label class="form-check-label" for="radioNextChangeOp">-->
<!--                                Change operation-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="form-check">-->
<!--                            <input class="form-check-input radioNextTask" type="radio" name="radioNextTask" id="radioNextRemove" value="4">-->
<!--                            <label class="form-check-label" for="radioNextRemove">-->
<!--                                Remove this task-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="form-check">-->
<!--                            <input class="form-check-input radioNextTask" type="radio" name="radioNextTask" id="radioNextNewTask" value="6">-->
<!--                            <label class="form-check-label" for="radioNextNewTask">-->
<!--                                Select a new task-->
<!--                            </label>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </form>-->
            </div>
            <div class="modal-footer">
                <!--                <form action='pp-machine-list-task.php' method='post'>-->
                <button type="button" id="modal_next_button_go" type='submit' class="btn btn-primary" disabled>Go!</button>
                <!--                </form>-->
            </div>
        </div>
    </div>
</div>
