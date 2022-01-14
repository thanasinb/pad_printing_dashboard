<?php
    ini_set('display_errors', 0);
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        require 'update/establish.php';
        if ($_POST['selected_radio']==1){
            $sql = "UPDATE machine_queue SET comp_date='0000-00-00', comp_time='00:00:00', id_task = (";
            $sql = $sql . "SELECT id_task FROM planning WHERE id_job=" . $_POST['id_job'] . " AND ";
            $sql = $sql . "operation=" . $_POST['operation_new'];
            $sql = $sql . ") WHERE id_machine='" . $_POST['id_machine'] . "' AND queue_number=1";
//            echo $sql;
            $conn->query($sql);
            $sql = "UPDATE machine_queue SET queue_number = queue_number - 1 WHERE id_machine='" . $_POST['id_mc'] . "' AND queue_number > 0";
            $conn->query($sql);
        }elseif ($_POST['selected_radio']==3) {
            $sql = "UPDATE planning SET task_complete=1 WHERE id_job=" . $_POST['id_job'] . " AND ";
            $sql = $sql . "operation=" . $_POST['operation'];
            $conn->query($sql);
        }elseif ($_POST['selected_radio']==4) {
            // FOR REMOVING TASK
            $sql = "DELETE FROM machine_queue WHERE id_machine='" . $_POST['id_mc'] . "' AND queue_number=1";
            $conn->query($sql);
//            echo  $sql;
        }elseif ($_POST['selected_radio']==5) {
            $sql = "UPDATE machine_queue SET queue_number = queue_number - 1 WHERE id_machine='" . $_POST['id_mc'] . "' AND queue_number > 0";
            $conn->query($sql);
        }elseif ($_POST['selected_radio']==6) {
            if ($_POST['is_current_task']==1){
                // FOR ADDING A NEW TASK TO QUEUE 1
                $sql = "INSERT INTO machine_queue (id_machine, queue_number, id_task) VALUES (";
                $sql = $sql . "'" . $_POST['id_machine'] . "',";
                $sql = $sql . "1,";
                $sql = $sql . "(SELECT id_task FROM planning WHERE ";
                $sql = $sql . "id_job=" . $_POST['id_job'] . " AND ";
                $sql = $sql . "operation=" . $_POST['operation_new'] . ")";
                $sql = $sql . ")";
            }else{
                // FOR ADDING A NEW TASK TO QUEUE 2
                $sql = "INSERT INTO machine_queue (id_machine, queue_number, id_task) VALUES (";
                $sql = $sql . "'" . $_POST['id_machine'] . "',";
                $sql = $sql . "2,";
                $sql = $sql . "(SELECT id_task FROM planning WHERE ";
                $sql = $sql . "id_job=" . $_POST['id_job'] . " AND ";
                $sql = $sql . "operation=" . $_POST['operation_new'] . ")";
                $sql = $sql . ")";
            }
            $conn->query($sql);
//            echo $sql;
        }
        require 'update/terminate.php';
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
        <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
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
        <script type="text/javascript" src="js/majorette/pp-machine-currentTaskModal.js"></script>
        <script type="text/javascript" src="js/majorette/pp-machine-refresh-2.js"></script>
        <script type="text/javascript" src="js/majorette/pp-machine-clock.js"></script>
    </head>
    <body class="nav-fixed">
    <?php require 'pp-machine-sidenavAccordion.php'; ?>
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
                                <div>
                                    <span class="hours"></span> :
                                    <span class="min"></span> :
                                    <span class="sec"></span>
                                </div>
                            </div>
                            <div class="card-body">
                                    <table id="datatablesSimple" class="table table-striped">
                                        <thead class="text-black" style="background-color: #ffea07">
                                        <?php require 'pp-machine-table-head.php' ?>
                                        </thead>
                                        <tbody>
                                        <?php
                                        require "pp-machine-list-machine-script-2.php";
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
                            <td>Qty accum: </td>
                            <td id="modal_qty_accum"></td>
                            <td></td>
                        </tr>
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
                                    Change operation
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input radioCurrentTask" type="radio" name="radioCurrentTask" id="radioForceStop" value="2">
                                <label class="form-check-label" for="radioForceStop">
                                    Force stop
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input radioCurrentTask" type="radio" name="radioCurrentTask" id="radioComplete" value="3">
                                <label class="form-check-label" for="radioComplete">
                                    Mark as complete
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input radioCurrentTask" type="radio" name="radioCurrentTask" id="radioRemove" value="4">
                                <label class="form-check-label" for="radioRemove">
                                    Remove this task
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input radioCurrentTask" type="radio" name="radioCurrentTask" id="radioNextQueue" value="5">
                                <label class="form-check-label" for="radioNextQueue">
                                    Feed task from next queue
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input radioCurrentTask" type="radio" name="radioCurrentTask" id="radioNewTask" value="6">
                                <label class="form-check-label" for="radioNewTask">
                                    Select a new task
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input radioCurrentTask" type="radio" name="radioCurrentTask" id="radioResetActivity" value="7">
                                <label class="form-check-label" for="radioResetActivity">
                                    Reset activity
                                </label>
                            </div>
                        </div>
                    </form>
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
                                    Change operation
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input radioNextTask" type="radio" name="radioNextTask" id="radioNextRemove" value="4">
                                <label class="form-check-label" for="radioNextRemove">
                                    Remove this task
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input radioNextTask" type="radio" name="radioNextTask" id="radioNextNewTask" value="6">
                                <label class="form-check-label" for="radioNextNewTask">
                                    Select a new task
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <!--                <form action='pp-machine-list-task.php' method='post'>-->
                    <button type="button" id="modal_next_button_go" type='submit' class="btn btn-primary" disabled>Go!</button>
                    <!--                </form>-->
                </div>
            </div>
        </div>
    </div>
        <script src="js/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/scripts.js"></script>
        <script src="js/Chart.js/2.9.4/Chart.min.js"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="js/simple-datatables@latest" type="text/javascript"></script>
        <script src="js/datatables/datatables-simple-demo.js"></script>
        <script src="js/litepicker/dist/bundle.js"></script>
        <script src="js/litepicker.js"></script>
    </body>
</html>
