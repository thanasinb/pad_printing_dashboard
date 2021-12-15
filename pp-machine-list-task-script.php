<?php
require 'update/establish.php';

$selected_radio = $_POST['selected_radio'];
$current_operation = $_POST['operation'];

// CHANGE OP, SAME JOB ID
if ($selected_radio==1){
    $sql = "SELECT * FROM planning WHERE id_job=" . $_POST["id_job"] . " AND status_backup=0 AND task_complete=0 AND 
    id_task IN (SELECT id_task FROM machine_queue WHERE id_machine='" . $_POST["id_mc"] . "') ORDER BY date_due ASC";
}

// SELECT NEW TASK
else{
    $sql = "SELECT * FROM planning WHERE id_task IN (SELECT id_task FROM machine_queue WHERE id_machine='" . $_POST["id_mc"] . "') 
    AND status_backup=0 AND task_complete=0 ORDER BY date_due ASC, id_job ASC";
}
$result_planning_machine = $conn->query($sql);

while($row_planning_machine = $result_planning_machine->fetch_assoc()){
    echo "<tr class=\"text-black fw-bold\">";
    echo "<td>";
//    if (intval($row_planning_machine['task_complete'])==0){
        if (($selected_radio==1) and (strcmp($current_operation,$row_planning_machine['operation'])!=0)) {
            echo "<button type='button' class='btn btn-blue btn-sm btn-machine'>";
            echo "Add to " . $_POST["id_mc"];
            echo "</button>";
        }
//    }else{
//        echo "<span class='me-1 text-black'>Complete!</span>";
//        echo "<i class='fas fa-check-circle fa-sm me-1 text-blue fs-6'></i>";
//    }
    echo "</td>";
    echo "<td id='td_job'>" . $row_planning_machine["id_job"] . "</td>";
    echo "<td>" . $row_planning_machine["work_order"] . "</td>";
    echo "<td>" . $row_planning_machine["item_no"] . "</td>";
    echo "<td>" . $row_planning_machine["machine"] . "</td>";
    echo "<td id='td_operation'>" . $row_planning_machine["operation"] . "</td>";
    echo "<td>" . $row_planning_machine["op_color"] . "</td>";
    echo "<td>" . $row_planning_machine["op_side"] . "</td>";
    echo "<td>" . number_format($row_planning_machine["qty_comp"]) . "/" . number_format($row_planning_machine["qty_order"]);
    $percent = round((intval($row_planning_machine["qty_comp"]) / intval($row_planning_machine["qty_order"])) * 100,0);
    echo "<div class=\"progress\">";
    echo "<div class=\"progress-bar\" role=\"progressbar\" style=\"width: " . $percent . "%\" ";
    echo "aria-valuenow=\"" . $percent . "\" aria-valuemin=\"0\" aria-valuemax=\"100\">" . $percent . "%";
    echo "</div></div>";
    echo "</td>";
    echo "<td>" . number_format($row_planning_machine["qty_open"]) . "</td>";
    $phpdate = strtotime($row_planning_machine["date_due"]);
    echo "<td>" . date( 'd-m-Y', $phpdate ) . "</td>";

    $sql = "SELECT id_machine, queue_number FROM machine_queue WHERE id_task =" . $row_planning_machine["id_task"] . " ORDER BY id_machine ASC";
    $result_machine_queue = $conn->query($sql);
    echo "<td>";
    while($row_machine_queue = $result_machine_queue->fetch_assoc()) {
        if (intval($row_machine_queue["queue_number"])==1)
            echo "<button type='button' class='btn btn-green btn-sm me-1 mb-1'>";
        else
            echo "<button type='button' class='btn btn-outline-green btn-sm me-1 mb-1'>";
        echo $row_machine_queue["id_machine"];
//        echo "<i class='fas fa-times-circle fs-6 ms-3'></i>";
        echo "</button>";
    }
    echo "</td>";
    echo "</tr>";
}

if ($selected_radio==1) {
    $sql = "SELECT * FROM planning WHERE id_job=" . $_POST["id_job"] . " AND status_backup=0 AND task_complete=0 AND 
    id_task NOT IN (SELECT id_task FROM machine_queue WHERE id_machine='" . $_POST["id_mc"] . "') ORDER BY date_due ASC, id_job ASC";
}
else{
    $sql = "SELECT * FROM planning WHERE id_task NOT IN (SELECT id_task FROM machine_queue WHERE id_machine='" . $_POST["id_mc"] . "') 
    AND status_backup=0 AND task_complete=0 ORDER BY date_due ASC, id_job ASC";
}
$result_planning_others = $conn->query($sql);

while($row_planning_other = $result_planning_others->fetch_assoc()){
    echo "<tr class=\"text-black fw-bold\">";
    echo "<td>";
//    if (intval($row_planning_other['task_complete'])==0) {
        echo "<button type='button' class='btn btn-blue btn-sm btn-machine'>";
        echo "Add to " . $_POST["id_mc"];
        echo "</button>";
//    }else{
//        echo "<span class='me-1 text-black'>Complete!</span>";
//        echo "<i class='fas fa-check-circle fa-sm me-1 text-blue fs-6'></i>";
//    }
    echo "</td>";
    echo "<td id='td_job'>" . $row_planning_other["id_job"] . "</td>";
    echo "<td>" . $row_planning_other["work_order"] . "</td>";
    echo "<td>" . $row_planning_other["item_no"] . "</td>";
    echo "<td>" . $row_planning_other["machine"] . "</td>";
    echo "<td id='td_operation'>" . $row_planning_other["operation"] . "</td>";
    echo "<td>" . $row_planning_other["op_color"] . "</td>";
    echo "<td>" . $row_planning_other["op_side"] . "</td>";
    echo "<td>" . number_format($row_planning_other["qty_comp"]) . "/" . number_format($row_planning_other["qty_order"]);
    $percent = round((intval($row_planning_other["qty_comp"]) / intval($row_planning_other["qty_order"])) * 100,0);
    echo "<div class=\"progress\">";
    echo "<div class=\"progress-bar\" role=\"progressbar\" style=\"width: " . $percent . "%\" ";
    echo "aria-valuenow=\"" . $percent . "\" aria-valuemin=\"0\" aria-valuemax=\"100\">" . $percent . "%";
    echo "</div></div>";
    echo "</td>";
    echo "<td>" . number_format($row_planning_other["qty_open"]) . "</td>";
    $phpdate = strtotime($row_planning_other["date_due"]);
    echo "<td>" . date( 'd-m-Y', $phpdate ) . "</td>";
    echo "<td>" . " " . "</td>";
    echo "</tr>\n";
}

require 'update/terminate.php';
?>
<div>
<form id="form_post" action="pp-machine.php" method="post">
    <input type="hidden" id="selected_radio" name="selected_radio" value="<?php echo $selected_radio; ?>">
    <input type="hidden" id="hidden_operation_new" name="operation_new" value="0">
    <input type="hidden" id="hidden_id_machine" name="id_machine" value="<?php echo $_POST["id_mc"]; ?>">
    <input type="hidden" id="hidden_id_job" name="id_job" value="<?php echo $_POST["id_job"]; ?>">
    <input type="hidden" id="hidden_current_task" name="is_current_task" value="<?php echo $_POST["is_current_task"]; ?>">
</form>
</div>