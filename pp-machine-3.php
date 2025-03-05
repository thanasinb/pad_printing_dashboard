<?php
require 'pp-session-start.php';

ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'update/establish.php';

    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'unknown_user';

    $id_job = $_POST['id_job'] ?? null;
    $id_machine = $_POST['id_machine'] ?? null;
    $operation_new = $_POST['operation_new'] ?? null;
    $selected_radio = $_POST['selected_radio'] ?? null;

   if ($selected_radio == 1) {
    // 🔹 ดึงข้อมูลเดิมของ Job ก่อนการแก้ไข
    $sql_old = "SELECT id_job, work_order, item_no, machine, operation, op_color, op_side, qty_comp, qty_open, date_due 
                FROM planning WHERE id_job = ? AND operation = ?";
    $stmt_old = $conn->prepare($sql_old);
    $stmt_old->bind_param("ss", $id_job, $operation_new);
    $stmt_old->execute();
    $result_old = $stmt_old->get_result();
    $old_data = $result_old->fetch_assoc();
    $stmt_old->close();

    // 🔹 UPDATE JOB ON MACHINE
    $sql_update = "UPDATE machine_queue SET comp_date='0000-00-00', comp_time='00:00:00', id_task = (
                    SELECT id_task FROM planning WHERE id_job=? AND operation=?
                ) WHERE id_machine=? AND queue_number=1";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("sss", $id_job, $operation_new, $id_machine);
    $stmt->execute();

    // 🔹 ดึงข้อมูลใหม่ของ Job หลังการแก้ไข
    $sql_new = "SELECT id_job, work_order, item_no, machine, operation, op_color, op_side, qty_comp, qty_open, date_due 
                FROM planning WHERE id_job = ? AND operation = ?";
    $stmt_new = $conn->prepare($sql_new);
    $stmt_new->bind_param("ss", $id_job, $operation_new);
    $stmt_new->execute();
    $result_new = $stmt_new->get_result();
    $new_data = $result_new->fetch_assoc();
    $stmt_new->close();

    // 🔹 เปรียบเทียบค่าก่อนและหลัง
    $changes = [];
    if ($old_data && $new_data) {
        foreach ($old_data as $key => $old_value) {
            $new_value = $new_data[$key] ?? null;
            if ($old_value != $new_value) {
                $changes[] = "แก้ไข " . ucfirst(str_replace("_", " ", $key)) . ": " . $old_value . " → " . $new_value;
            }
        }
    }

    // 🔹 ดึงข้อมูล Job จาก planning (เหมือนเดิม)
    $sql_planning = "SELECT id_job, work_order, item_no, machine, operation, op_color, op_side, qty_comp, qty_open, date_due 
                    FROM planning WHERE id_job = ? AND operation = ?";
    $stmt_planning = $conn->prepare($sql_planning);
    $stmt_planning->bind_param("ss", $id_job, $operation_new);
    $stmt_planning->execute();
    $result_planning = $stmt_planning->get_result();
    $job_details = $result_planning->fetch_assoc();
    $stmt_planning->close();

    if ($job_details) {
        $details = json_encode([
            "Job ID: " . $job_details['id_job'],
            "Work Order: " . $job_details['work_order'],
            "Item No.: " . $job_details['item_no'],
            "Machine: " . $job_details['machine'],
            "Operation: " . $job_details['operation'],
            "Color: " . $job_details['op_color'],
            "Side: " . $job_details['op_side'],
            "Qty Comp.: " . $job_details['qty_comp'],
            "Qty Open: " . $job_details['qty_open'],
            "Due Date: " . $job_details['date_due']
        ], JSON_UNESCAPED_UNICODE);
    } else {
        $details = json_encode(["Error: ไม่พบข้อมูลใน planning"], JSON_UNESCAPED_UNICODE);
    }

    // 🔹 รวมข้อมูลที่เปลี่ยนแปลงเข้าไปใน details
    if (!empty($changes)) {
        $details = json_encode($changes, JSON_UNESCAPED_UNICODE);
    }

    // 🔹 บันทึกลง history
    logHistory($username, "แก้ไข job ID $id_job ที่ machine $id_machine.", $details, $conn);

    } elseif ($selected_radio == 4) {
        // REMOVE TASK
        $sql = "DELETE FROM machine_queue WHERE id_machine=? AND queue_number=1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $_POST['id_mc']);
        $stmt->execute();

        // บันทึกประวัติ
        logHistory($username, "เอา job ID $id_job ออกจาก machine $id_machine.", "", $conn);
    } elseif ($selected_radio == 6) {
        $is_current_task = $_POST['is_current_task'] ?? 0;
        $queue_number = ($is_current_task == 1) ? 1 : 2;

        // ADD NEW TASK TO QUEUE
        $sql = "INSERT INTO machine_queue (id_machine, queue_number, id_task) VALUES (?, ?, 
            (SELECT id_task FROM planning WHERE id_job=? AND operation=?))";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $id_machine, $queue_number, $id_job, $operation_new);
        $stmt->execute();

        // ดึงข้อมูล Job จาก planning
        $sql_planning = "SELECT id_job, work_order, item_no, machine, operation, op_color, op_side, qty_comp, qty_open, date_due 
                     FROM planning WHERE id_job = ? AND operation = ?";
        $stmt_planning = $conn->prepare($sql_planning);
        $stmt_planning->bind_param("ss", $id_job, $operation_new);
        $stmt_planning->execute();
        $result_planning = $stmt_planning->get_result();
        $job_details = $result_planning->fetch_assoc();
        $stmt_planning->close();

        if ($job_details) {
            $details = json_encode([
                "Job ID: " . $job_details['id_job'],
                "Work Order: " . $job_details['work_order'],
                "Item No.: " . $job_details['item_no'],
                "Machine Type: " . $job_details['machine'],
                "Operation: " . $job_details['operation'],
                "Color: " . $job_details['op_color'],
                "Side: " . $job_details['op_side'],
                "Qty Comp.: " . $job_details['qty_comp'],
                "Qty Open: " . $job_details['qty_open'],
                "Due Date: " . $job_details['date_due']
            ], JSON_UNESCAPED_UNICODE);
        } else {
            $details = json_encode(["Error: ไม่พบข้อมูลใน planning"], JSON_UNESCAPED_UNICODE);
        }

        // บันทึกลง history
        logHistory($username, "เพิ่ม job ID $id_job ไปที่ machine $id_machine.", $details, $conn);

    }
    elseif ($selected_radio == 7) { // 7 = แก้ไข Job
        $id_job = $_POST['id_job'];
        $id_machine = $_POST['id_machine'];
        $operation_new = $_POST['operation_new'];

        // ดึงข้อมูล Job เดิมจาก machine_queue
        $sql_old = "SELECT id_task FROM machine_queue WHERE id_machine=? AND id_task IN 
                (SELECT id_task FROM planning WHERE id_job=? AND operation=?)";
        $stmt_old = $conn->prepare($sql_old);
        $stmt_old->bind_param("sss", $id_machine, $id_job, $operation_new);
        $stmt_old->execute();
        $result_old = $stmt_old->get_result();
        $old_task = $result_old->fetch_assoc();
        $stmt_old->close();

        // ดึงข้อมูล Job ใหม่จาก planning
        $sql_planning = "SELECT id_job, work_order, item_no, machine, operation, op_color, op_side, qty_comp, qty_open, date_due 
                     FROM planning WHERE id_job=? AND operation=?";
        $stmt_planning = $conn->prepare($sql_planning);
        $stmt_planning->bind_param("ss", $id_job, $operation_new);
        $stmt_planning->execute();
        $result_planning = $stmt_planning->get_result();
        $new_task = $result_planning->fetch_assoc();
        $stmt_planning->close();

        $changes = [];

        if ($old_task['id_task'] !== $new_task['id_job']) {
            $changes[] = "Job ID: " . $old_task['id_task'] . " → " . $new_task['id_job'];
        }
        if ($old_task['operation'] !== $new_task['operation']) {
            $changes[] = "Operation: " . $old_task['operation'] . " → " . $new_task['operation'];
        }

        if (!empty($changes)) {
            $details = json_encode($changes, JSON_UNESCAPED_UNICODE);
            logHistory($username, "แก้ไข job ID $id_job บน machine $id_machine.", $details, $conn);

            // อัปเดต machine_queue
            $sql_update = "UPDATE machine_queue SET id_task = (SELECT id_task FROM planning WHERE id_job=? AND operation=?)
                       WHERE id_machine=? AND id_task=?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ssss", $id_job, $operation_new, $id_machine, $old_task['id_task']);
            $stmt_update->execute();
        }
    }
    elseif (!empty($_POST["id_mc"])) {
        $id_mc = $_POST["id_mc"];
        $id_mc_type = $_POST["id_mc_type"];
        $id_cam = $_POST["id_cam"];
        $mc_des = $_POST["mc_des"];

        // ตรวจสอบว่า Machine ID มีอยู่หรือไม่
        $sql = "SELECT id_mc FROM machine WHERE id_mc=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $id_mc);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // ดึงชื่อ Machine Type
            $sql_mc_type = "SELECT mc_type FROM machine_type WHERE id_mc_type=?";
            $stmt_mc_type = $conn->prepare($sql_mc_type);
            $stmt_mc_type->bind_param("s", $id_mc_type);
            $stmt_mc_type->execute();
            $result_mc_type = $stmt_mc_type->get_result();
            $mc_type = ($result_mc_type->num_rows > 0) ? $result_mc_type->fetch_assoc()['mc_type'] : "Unknown Type";
            $stmt_mc_type->close();

            // เพิ่มเครื่องจักรใหม่
            $sql = "INSERT INTO machine (id_mc, id_mc_type, id_cam, mc_des, time_contact) 
                    VALUES (?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $id_mc, $id_mc_type, $id_cam, $mc_des);

            if ($stmt->execute()) {
                // ➜ บันทึก History
                $action = "สร้าง Machine ID: " . $id_mc;
                $details = json_encode([
                    "Machine ID: " . $id_mc,
                    "Machine Type: " . $mc_type,
                    "Camera ID: " . $id_cam,
                    "Description: " . $mc_des
                ], JSON_UNESCAPED_UNICODE);
                logHistory($username, $action, $details, $conn);


            }
        } else {
            // ดึงข้อมูลเดิมของเครื่องจักร
            $sql_old = "SELECT id_mc_type, id_cam, mc_des FROM machine WHERE id_mc=?";
            $stmt_old = $conn->prepare($sql_old);
            $stmt_old->bind_param("s", $id_mc);
            $stmt_old->execute();
            $result_old = $stmt_old->get_result();
            $old_data = $result_old->fetch_assoc();

            // ดึงชื่อ Machine Type
            $sql_mc_type = "SELECT mc_type FROM machine_type WHERE id_mc_type=?";
            $stmt_mc_type = $conn->prepare($sql_mc_type);
            $stmt_mc_type->bind_param("s", $id_mc_type);
            $stmt_mc_type->execute();
            $result_mc_type = $stmt_mc_type->get_result();
            $mc_type_new = ($result_mc_type->num_rows > 0) ? $result_mc_type->fetch_assoc()['mc_type'] : "Unknown Type";
            $stmt_mc_type->close();

            $changes = [];

            if ($old_data['id_mc_type'] != $id_mc_type) {
                $changes[] = "แก้ไข Machine Type: " . $old_data['id_mc_type'] . " → " . $mc_type_new;
            }
            if ($old_data['id_cam'] != $id_cam) {
                $changes[] = "แก้ไข Camera ID: " . $old_data['id_cam'] . " → " . $id_cam;
            }
            if ($old_data['mc_des'] != $mc_des) {
                $changes[] = "แก้ไข Machine Description: " . $old_data['mc_des'] . " → " . $mc_des;
            }

            if (!empty($changes)) {
                $action = "แก้ไขเครื่องจักร ID: " . $id_mc;
                $details = json_encode($changes, JSON_UNESCAPED_UNICODE);
                logHistory($username, $action, $details, $conn);


                // อัปเดตข้อมูลเครื่องจักร
                $sql_update = "UPDATE machine SET id_mc_type=?, id_cam=?, mc_des=? WHERE id_mc=?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("ssss", $id_mc_type, $id_cam, $mc_des, $id_mc);
                $stmt_update->execute();
            }
        }
    }

    require 'update/terminate.php';
}

// ฟังก์ชันบันทึกประวัติ
function logHistory($username, $action, $details, $conn) {
    $sql_log = "INSERT INTO history (username, action, details, date_time) VALUES (?, ?, ?, NOW())";
    $stmt_log = $conn->prepare($sql_log);
    $stmt_log->bind_param("sss", $username, $action, $details);
    $stmt_log->execute();
    $stmt_log->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Job overview by Machine</title>
    <link href="css/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/litepicker/dist/css/litepicker.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/machine-learning.png" />
    <script data-search-pseudo-elements defer src="js/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="js/feather-icons/4.28.0/feather.min.js"></script>
    <!--        <link rel="stylesheet" href="css/reorder-columns/dragtable.css">-->
    <!--        <link rel="stylesheet" href="css/reorder-columns/bootstrap-table.min.css">-->
    <link rel="stylesheet" href="css/majorette.css">
    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/jquery/jquery-ui.min.js"></script>
    <!--        <script src="js/reorder-columns/jquery.dragtable.js"></script>-->
    <!--        <script src="js/reorder-columns/bootstrap-table.min.js"></script>-->
    <!--        <script src="js/reorder-columns/bootstrap-table-reorder-columns.js"></script>-->
    <!--        <script src="js/majorette/pp-dragtable.js"></script>-->
    <!--        <script type="text/javascript" src="js/datetimepicker4/moment.min.js"></script>-->
    <!--        <script type="text/javascript" src="js/datetimepicker4/tempusdominus-bootstrap-4.min.js"></script>-->
    <!--        <link rel="stylesheet" href="css/datetimepicker4/tempusdominus-bootstrap-4.min.css" />-->
    <?php
    //        require 'js/majorette/date_picker.php'
    ?>
    <!--        <script type="text/javascript" src="js/majorette/pp-machine-assign-date.js"></script>-->
    <!--        <script type="text/javascript" src="js/majorette/pp-machine-multiplier.js"></script>-->
    <!--        <script type="text/javascript" src="js/majorette/pp-machine-currentTaskModal.js"></script>-->
    <script type="text/javascript" src="js/majorette/pp-machine-refresh-3.js"></script>
    <script type="text/javascript" src="js/majorette/pp-machine-clock.js"></script>
</head>
<body class="nav-fixed">
<?php require 'pp-staff-sidenavAccordion.php'; ?>
<div id="layoutSidenav">
    <?php require 'pp-layoutSidenav_nav.php'; ?>

    <div id="layoutSidenav_content">
        <main>
            <header class="page-header page-header-dark pb-5">
                <div class="container-xl px-4">
                    <div class="page-header-content pt-4">
                    </div>
                </div>
            </header>
            <!-- Main page content-->
            <div class="container-fluid px-4 mt-n10">
                <!-- Example DataTable for Dashboard Demo-->
                <div class="card mb-4 w-100" id="table-machine">
                    <div class="card-header bg-red fw-bold text-white fs-4 d-flex justify-content-between">
                        <div>Job overview by Machine</div>
<!--                        <div>-->
<!--                            <span class="hours"></span> :-->
<!--                            <span class="min"></span> :-->
<!--                            <span class="sec"></span>-->
<!--                        </div>-->
                    </div>
                    <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="checkbox_hide_unassigned_machines" id="checkbox_hide_unassigned_machines" checked>
                            <label class="form-check-label" for="checkbox_hide_unassigned_machines">
                                Hide unassigned machines (ซ่อนเครื่องไม่มีงาน)
                            </label>
                        </div>
                        <table id="datatablesSimple" class="table table-striped" style="width: 100%; white-space: nowrap">
                            <thead class="text-black" style="background-color: #ffea07">
                            <?php
                            require 'pp-machine-table-head-3.php'
                            ?>
                            </thead>
                            <tbody id="table_body">
                            <?php
                            //                                    require "pp-machine-list-machine-script-3.php";
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<div class="modal fade" id="currentTaskModal" tabindex="-1" aria-labelledby="currentTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="currentTaskModalLabel">Current task for machine: </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="modal_table_current" class="table table-striped">
                    <tr>
                        <td>Machine ID: </td>
                        <td id="modal_id_machine"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Item NO: </td>
                        <td id="modal_item_no"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Operation</td>
                        <td id="modal_operation"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Date due: </td>
                        <td id="modal_date_due"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Qty/Tray: </td>
                        <td><input type="number" id="modal_qty_per_tray" name="modal_qty_per_tray" disabled></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Qty/Shif: </td>
                        <td><input type="number" id="modal_qty_shif" name="modal_qty_shif" disabled></td>
                        <td></td>
                    </tr>
                    <!--                        <tr>-->
                    <!--                            <td>Qty accum: </td>-->
                    <!--                            <td id="modal_qty_accum"></td>-->
                    <!--                            <td></td>-->
                    <!--                        </tr>-->
                    <tr>
                        <td>Qty order: </td>
                        <td id="modal_qty_order"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Qty percent: </td>
                        <td id="modal_qty_percent"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Task ID: </td>
                        <td id="modal_id_task"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Job ID: </td>
                        <td id="modal_id_job"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Last update: </td>
                        <td id="modal_last_update"></td>
                        <td></td>
                    </tr>
                </table>
                <br>
                <h5>Action: </h5>
                <form id="form_modal_current_task" method="post">
                    <input type="hidden" id="selected_radio" name="selected_radio" value="0">
                    <input type="hidden" id="hidden_id_job" name="id_job" value="0">
                    <input type="hidden" id="hidden_id_machine" name="id_mc" value="0">
                    <input type="hidden" id="hidden_item_no" name="hidden_item_no" value="0">
                    <input type="hidden" id="hidden_operation" name="operation" value="0">
                    <input type="hidden" id="hidden_current_task" name="is_current_task" value="1">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input radioCurrentTask" type="radio" name="radioCurrentTask" id="radioChangeOp" value="1">
                            <label class="form-check-label" for="radioChangeOp">
                                Change operation (เปลี่ยน Operation)
                            </label>
                        </div>
                        <!--                            <div class="form-check">-->
                        <!--                                <input class="form-check-input radioCurrentTask" type="radio" name="radioCurrentTask" id="radioForceStop" value="2">-->
                        <!--                                <label class="form-check-label" for="radioForceStop">-->
                        <!--                                    Force stop-->
                        <!--                                </label>-->
                        <!--                            </div>-->
                        <!--                            <div class="form-check">-->
                        <!--                                <input class="form-check-input radioCurrentTask" type="radio" name="radioCurrentTask" id="radioComplete" value="3">-->
                        <!--                                <label class="form-check-label" for="radioComplete">-->
                        <!--                                    Mark as complete (จบงาน)-->
                        <!--                                </label>-->
                        <!--                            </div>-->
                        <div class="form-check">
                            <input class="form-check-input radioCurrentTask" type="radio" name="radioCurrentTask" id="radioRemove" value="4">
                            <label class="form-check-label" for="radioRemove">
                                Remove this task (เอางานออก)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input radioCurrentTask" type="radio" name="radioCurrentTask" id="radioNextQueue" value="5">
                            <label class="form-check-label" for="radioNextQueue">
                                Feed task from next queue (ดึงงานจากคิวถัดไป)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input radioCurrentTask" type="radio" name="radioCurrentTask" id="radioNewTask" value="6">
                            <label class="form-check-label" for="radioNewTask">
                                Select a new task (เพิ่มงานใหม่)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input radioCurrentTask" type="radio" name="radioCurrentTask" id="radioResetActivity" value="7">
                            <label class="form-check-label" for="radioResetActivity">
                                Reset activity (รีเซ็ตงาน)
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" id="modal_button_change" class="btn btn-primary mr-auto">Change</button>
                <button type="button" id="modal_button_save" class="btn btn-primary mr-auto">Save</button>
                <button type="button" id="modal_button_go" type='submit' class="btn btn-primary" disabled>Go!</button>
                <!--                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>-->
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
                <table id="modal_table_next" class="table table-striped">
                    <tr>
                        <td>Machine ID: </td>
                        <td id="modal_next_id_machine"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Item NO: </td>
                        <td id="modal_next_item_no"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Operation</td>
                        <td id="modal_next_operation"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Date due: </td>
                        <td id="modal_next_date_due"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Qty per tray: </td>
                        <td id="modal_next_qty_per_tray"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Qty accum: </td>
                        <td id="modal_next_qty_accum"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Qty order: </td>
                        <td id="modal_next_qty_order"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Qty percent: </td>
                        <td id="modal_next_qty_percent"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Task ID: </td>
                        <td id="modal_next_id_task"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Job ID: </td>
                        <td id="modal_next_id_job"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Last update: </td>
                        <td id="modal_next_last_update"></td>
                        <td></td>
                    </tr>
                </table>
                <br>
                <h5>Action: </h5>
                <form id="form_modal_next_task" method="post">
                    <input type="hidden" id="next_selected_radio" name="selected_radio" value="0">
                    <input type="hidden" id="next_hidden_id_job" name="id_job" value="0">
                    <input type="hidden" id="next_hidden_id_machine" name="id_mc" value="0">
                    <input type="hidden" id="next_hidden_item_no" name="hidden_item_no" value="0">
                    <input type="hidden" id="next_hidden_operation" name="operation" value="0">
                    <input type="hidden" id="next_hidden_current_task" name="is_current_task" value="0">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input radioNextTask" type="radio" name="radioNextTask" id="radioNextChangeOp" value="1">
                            <label class="form-check-label" for="radioNextChangeOp">
                                Change operation (เปลี่ยน Operation)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input radioNextTask" type="radio" name="radioNextTask" id="radioNextRemove" value="4">
                            <label class="form-check-label" for="radioNextRemove">
                                Remove this task (เอางานออก)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input radioNextTask" type="radio" name="radioNextTask" id="radioNextNewTask" value="6">
                            <label class="form-check-label" for="radioNextNewTask">
                                Select a new task (เพิ่มงานใหม่)
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="modal_next_button_go" type='submit' class="btn btn-primary" disabled>Go!</button>
            </div>
        </div>
    </div>
</div>

<script src="js/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
<!--        <script src="js/Chart.js/2.9.4/Chart.min.js"></script>-->
<!--        <script src="assets/demo/chart-area-demo.js"></script>-->
<!--        <script src="assets/demo/chart-bar-demo.js"></script>-->
<script src="js/simple-datatables@latest" type="text/javascript"></script>
<script src="js/datatables/datatables-simple-demo.js"></script>
<script src="js/litepicker/dist/bundle.js"></script>
<script src="js/litepicker.js"></script>

</body>
</html>