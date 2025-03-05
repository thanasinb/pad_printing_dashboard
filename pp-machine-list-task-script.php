<?php
require 'update/establish.php';

$selected_radio = isset($_POST['selected_radio']) ? intval($_POST['selected_radio']) : 0;
$current_operation = isset($_POST['operation']) ? $_POST['operation'] : '';
$id_job = isset($_POST['id_job']) ? $_POST['id_job'] : '';
$id_machine = isset($_POST['id_mc']) ? $_POST['id_mc'] : '';

if ($selected_radio == 1) {
    $sql = "SELECT * FROM planning 
            WHERE id_job = ? 
            AND status_backup = 0 
            AND id_task IN (SELECT id_task FROM machine_queue WHERE id_machine = ?) 
            ORDER BY date_due ASC";
} else {
    $sql = "SELECT * FROM planning 
            WHERE id_task IN (SELECT id_task FROM machine_queue WHERE id_machine = ?) 
            AND status_backup = 0 
            ORDER BY date_due ASC, id_job ASC";
}

$stmt = $conn->prepare($sql);
if ($selected_radio == 1) {
    $stmt->bind_param("ss", $id_job, $id_machine);
} else {
    $stmt->bind_param("s", $id_machine);
}

$stmt->execute();
$result_planning_machine = $stmt->get_result();

// แสดงรายการงานในเครื่องจักรปัจจุบัน
while ($row_planning_machine = $result_planning_machine->fetch_assoc()) {
    echo "<tr class=\"text-black fw-bold\">";
    echo "<td>";
    if ($selected_radio == 1 && strcmp($current_operation, $row_planning_machine['operation']) != 0) {
        echo "<button type='button' class='btn btn-blue btn-sm btn-machine'>";
        echo "Add to " . htmlspecialchars($id_machine);
        echo "</button>";
    }
    echo "</td>";
    echo "<td id='td_job'>" . htmlspecialchars($row_planning_machine["id_job"]) . "</td>";
    echo "<td>" . htmlspecialchars($row_planning_machine["work_order"]) . "</td>";
    echo "<td>" . htmlspecialchars($row_planning_machine["item_no"]) . "</td>";
    echo "<td>" . htmlspecialchars($row_planning_machine["machine"]) . "</td>";
    echo "<td id='td_operation'>" . htmlspecialchars($row_planning_machine["operation"]) . "</td>";
    echo "<td>" . htmlspecialchars($row_planning_machine["op_color"]) . "</td>";
    echo "<td>" . htmlspecialchars($row_planning_machine["op_side"]) . "</td>";
    echo "<td>" . number_format($row_planning_machine["qty_comp"]) . "/" . number_format($row_planning_machine["qty_order"]);

    $percent = round(($row_planning_machine["qty_comp"] / $row_planning_machine["qty_order"]) * 100, 0);
    echo "<div class=\"progress\">";
    echo "<div class=\"progress-bar\" role=\"progressbar\" style=\"width: " . $percent . "%\" aria-valuenow=\"" . $percent . "\" aria-valuemin=\"0\" aria-valuemax=\"100\">" . $percent . "%</div>";
    echo "</div></td>";

    echo "<td>" . number_format($row_planning_machine["qty_open"]) . "</td>";
    echo "<td>" . date('d-m-Y', strtotime($row_planning_machine["date_due"])) . "</td>";

    // แสดงปุ่มของเครื่องจักรในคิว
    $sub_sql = "SELECT id_machine, queue_number FROM machine_queue WHERE id_task = ? ORDER BY id_machine ASC";
    $sub_stmt = $conn->prepare($sub_sql);
    $sub_stmt->bind_param("i", $row_planning_machine["id_task"]);
    $sub_stmt->execute();
    $result_machine_queue = $sub_stmt->get_result();

    echo "<td>";
    while ($row_machine_queue = $result_machine_queue->fetch_assoc()) {
        $btn_class = intval($row_machine_queue["queue_number"]) == 1 ? 'btn-green' : 'btn-outline-green';
        echo "<button type='button' class='btn $btn_class btn-sm me-1 mb-1'>";
        echo htmlspecialchars($row_machine_queue["id_machine"]);
        echo "</button>";
    }
    echo "</td>";
    echo "</tr>";
}

// การแสดงรายการงานที่ยังไม่ได้เพิ่มในเครื่องจักร
if ($selected_radio == 1) {
    $sql_others = "SELECT * FROM planning 
                   WHERE id_job = ? 
                   AND status_backup = 0 
                   AND id_task NOT IN (SELECT id_task FROM machine_queue WHERE id_machine = ?) 
                   ORDER BY date_due ASC, id_job ASC";
    $stmt_others = $conn->prepare($sql_others);
    $stmt_others->bind_param("ss", $id_job, $id_machine);
} else {
    $sql_others = "SELECT * FROM planning 
                   WHERE id_task NOT IN (SELECT id_task FROM machine_queue WHERE id_machine = ?) 
                   AND status_backup = 0 
                   ORDER BY date_due ASC, id_job ASC";
    $stmt_others = $conn->prepare($sql_others);
    $stmt_others->bind_param("s", $id_machine);
}

$stmt_others->execute();
$result_planning_others = $stmt_others->get_result();

while ($row_planning_other = $result_planning_others->fetch_assoc()) {
    echo "<tr class=\"text-black fw-bold\">";
    echo "<td><button type='button' class='btn btn-blue btn-sm btn-machine'>Add to " . htmlspecialchars($id_machine) . "</button></td>";
    echo "<td id='td_job'>" . htmlspecialchars($row_planning_other["id_job"]) . "</td>";
    echo "<td>" . htmlspecialchars($row_planning_other["work_order"]) . "</td>";
    echo "<td>" . htmlspecialchars($row_planning_other["item_no"]) . "</td>";
    echo "<td>" . htmlspecialchars($row_planning_other["machine"]) . "</td>";
    echo "<td id='td_operation'>" . htmlspecialchars($row_planning_other["operation"]) . "</td>";
    echo "<td>" . htmlspecialchars($row_planning_other["op_color"]) . "</td>";
    echo "<td>" . htmlspecialchars($row_planning_other["op_side"]) . "</td>";
    echo "<td>" . number_format($row_planning_other["qty_comp"]) . "/" . number_format($row_planning_other["qty_order"]);
    $percent = round(($row_planning_other["qty_comp"] / $row_planning_other["qty_order"]) * 100, 0);
    echo "<div class=\"progress\"><div class=\"progress-bar\" role=\"progressbar\" style=\"width: " . $percent . "%\" aria-valuenow=\"" . $percent . "\" aria-valuemin=\"0\" aria-valuemax=\"100\">" . $percent . "%</div></div></td>";
    echo "<td>" . number_format($row_planning_other["qty_open"]) . "</td>";
    echo "<td>" . date('d-m-Y', strtotime($row_planning_other["date_due"])) . "</td>";
    echo "<td></td>";

    // ปุ่ม "แก้ไข" และ "ลบ"
    echo "<td class='text-center'>
        <button type='button' 
                name='job_info_edit' 
                data-bs-toggle='modal' 
                data-bs-target='#setting_job_modal' 
                class='btn btn-datatable btn-icon text-black me-2 job_info_edit'
                data-job-id='" . htmlspecialchars($row_planning_other["id_job"]) . "' 
                data-job-operation='" . htmlspecialchars($row_planning_other["operation"]) . "' 
                data-job-machine='" . htmlspecialchars($row_planning_other["machine"]) . "' 
                data-job-workorder='" . htmlspecialchars($row_planning_other["work_order"]) . "' 
                data-job-item='" . htmlspecialchars($row_planning_other["item_no"]) . "' 
                data-job-color='" . htmlspecialchars($row_planning_other["op_color"]) . "' 
                data-job-side='" . htmlspecialchars($row_planning_other["op_side"]) . "' 
                data-job-opdes='" . htmlspecialchars($row_planning_other["op_des"] ?? '') . "' 
                data-job-due='" . htmlspecialchars($row_planning_other["date_due"] ?? '') . "'>
            <i class='far fa-edit fs-6'></i>
        </button>
        
        <button type='button' 
                name='job_info_delete'
                data-bs-toggle='modal' 
                data-bs-target='#job_info_delete' 
                class='btn btn-datatable btn-icon text-black me-2 job_info_delete'
                data-job-id='" . htmlspecialchars($row_planning_other["id_job"]) . "' 
                data-job-operation='" . htmlspecialchars($row_planning_other["operation"]) . "' 
                data-job-machine='" . htmlspecialchars($row_planning_other["machine"]) . "' 
                data-job-opdes='" . htmlspecialchars($row_planning_other["op_des"] ?? '') . "' 
                data-job-datedue='" . htmlspecialchars($row_planning_other["date_due"] ?? '') . "'>
            <i class='fas fa-trash'></i>
        </button>
      </td>";
    echo "</tr>";
}

$stmt->close();
$stmt_others->close();
$conn->close();
?>
<div>
<form id="form_post" action="pp-machine-3.php" method="post">
    <input type="hidden" id="selected_radio" name="selected_radio" value="<?php echo $selected_radio; ?>">
    <input type="hidden" id="hidden_operation_new" name="operation_new" value="0">
    <input type="hidden" id="hidden_id_machine" name="id_machine" value="<?php echo $_POST["id_mc"]; ?>">
    <input type="hidden" id="hidden_id_job" name="id_job" value="<?php echo $_POST["id_job"]; ?>">
    <input type="hidden" id="hidden_current_task" name="is_current_task" value="<?php echo $_POST["is_current_task"]; ?>">
</form>
</div>