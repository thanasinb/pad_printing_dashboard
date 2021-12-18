<?php
    ini_set('display_errors', 0);
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
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
        <link rel="stylesheet" href="css/reorder-columns/dragtable.css">
        <link rel="stylesheet" href="css/reorder-columns/bootstrap-table.min.css">
        <link rel="stylesheet" href="css/majorette.css">
        <script src="js/jquery/jquery.min.js"></script>
        <script src="js/jquery/jquery-ui.min.js"></script>
        <script src="js/reorder-columns/jquery.dragtable.js"></script>
        <script src="js/reorder-columns/bootstrap-table.min.js"></script>
        <script src="js/reorder-columns/bootstrap-table-reorder-columns.js"></script>
<!--        <script src="js/majorette/pp-dragtable.js"></script>-->
        <script type="text/javascript" src="js/datetimepicker4/moment.min.js"></script>
        <script type="text/javascript" src="js/datetimepicker4/tempusdominus-bootstrap-4.min.js"></script>
        <link rel="stylesheet" href="css/datetimepicker4/tempusdominus-bootstrap-4.min.css" />
        <script type="text/javascript" src="js/majorette/pp-machine-staff.js"></script>
<!--        <script type="text/javascript" src="js/majorette/pp-machine-currentTaskModal.js"></script>-->
<!--        <script type="text/javascript" src="js/majorette/pp-machine-refresh.js"></script>-->
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
                            <div class="card-header bg-red fw-bold text-white fs-4">Staff List</div>
                            <div class="card-body">
                                <table id="datatablesSimple" class="table table-striped">
                                    <thead class="text-black" style="background-color: #ffea07"><?php require 'pp-staff-table-head.php' ?></thead>
                                    <tbody><?php require "pp-staff-list-staff-script-operator.php"; ?></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    <div class="modal fade" id="staff_modal" tabindex="-1" role="dialog" aria-labelledby="staff_modal_label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staff_modal_label">Staff</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span id="modal_span_staff_id"></span>
                    <table id="modal_table" class="table table-striped">
                        <tr>
                            <td>Staff ID</td>
                            <td id="modal_staff_id"><input type="text" id="input_staff_id" name="input_staff_id" disabled></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>RFID</td>
                            <td id="modal_rfid"><input type="text" id="input_rfid" name="input_rfid" disabled></td>
                            <td>
<!--                                <button id="button_rfid" class="btn btn-primary btn-sm" type="button">Change</button>-->
<!--                                <button id="button_save_rfid" class="btn btn-primary btn-sm" type="button">Save</button>-->
                            </td>
                        </tr>
                        <tr>
                            <td>Prefix</td>
                            <td > <form>
                                    <select name="prefix_name" id="prefix_name"disabled>
                                        <option value=" ">กรุณาเลือก...</option>
                                        <option value="1">นาย</option>
                                        <option value="2">นาง</option>
                                        <option value="3">นางสาว</option>
                                    </select>
                                </form></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>First name</td>
                            <td id="modal_name"><input type="text" id="input_name" name="input_name" disabled></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Last name</td>
                            <td id="modal_last"><input type="text" id="input_last" name="input_last" disabled></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Site</td>
                            <td id="modal_site"><input type="text" id="input_staff_id" name="input_staff_id" disabled></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Role</td>
                            <td > <form>
                                    <select name="role" id="role"disabled>
                                        <option value=" ">กรุณาเลือก...</option>
                                        <option value="1">Operator</option>
                                        <option value="2">Technician</option>
                                    </select>
                                </form></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Shif</td>
                            <td > <form>
                                    <select name="shift" id="shift"disabled>
                                        <option value=" ">กรุณาเลือก...</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                    </select>
                                </form></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button id="button_rfid" class="btn btn-primary" type="button">Change</button>
                    <button id="button_save_rfid" class="btn btn-primary" type="button">Save</button>
                    <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Close</button>
<!--                    <button class="btn btn-primary" type="button">Save changes</button>-->
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
