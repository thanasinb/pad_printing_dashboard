<?php

require 'pp-session-start.php';

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
    <title>Downtime Code Setup</title>
    <link href="css/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/litepicker/dist/css/litepicker.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/settings.png" />
    <script data-search-pseudo-elements defer src="js/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="js/feather-icons/4.28.0/feather.min.js"></script>
    <link rel="stylesheet" href="css/majorette.css">
    <link rel="stylesheet" href="css/reorder-columns/dragtable.css">
    <link rel="stylesheet" href="css/reorder-columns/bootstrap-table.min.css">
<!--    <script src="js/jquery/jquery.min.js"></script>-->
    <script src="js/jquery/jquery-ui.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="js/majorette/pp-setting-mc-info.js"></script>


</head>
<style>

    /* เอฟเฟกต์ปุ่ม Confirm (สีน้ำเงิน) */
    .btn-confirm-modal {
        transition: all 0.2s ease-in-out;
        background-color: #007bff; /* สีน้ำเงิน */
        color: white !important;
        font-size: 16px;
        font-weight: bold;
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        display: inline-block;
        cursor: pointer;
        text-align: center;

    }

    /* เมื่อเมาส์ไปชี้ที่ปุ่ม Confirm */
    .btn-confirm-modal:hover {
        transform: scale(1.1);
        opacity: 0.9;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        background-color: #0069d9; /* สีน้ำเงินเข้มขึ้น */
    }

    /* เอฟเฟกต์ตอนกดปุ่ม Confirm */
    .btn-confirm-modal:active {
        transform: scale(0.95);
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.15);
        background-color: #0056b3;
    }


    /* ปุ่มใน Dark Mode */
    body.dark-mode .btn-confirm-modal {
        background-color: #375a7f !important; /* สีฟ้าหม่น */
        color: #ffffff !important; /* สีข้อความขาว */
        border: 1px solid #444444 !important; /* เส้นขอบเข้ม */
    }

    /* Hover ใน Dark Mode */
    body.dark-mode .btn-confirm-modal:hover {
        background-color: #3b566a !important; /* สีฟ้าหม่นเข้มขึ้นเมื่อ hover */
    }

    /* เอฟเฟกต์ปุ่ม Close (สีม่วง) */
    .btn-close-modal {
        transition: all 0.2s ease-in-out;
        background-color:  #f44336; /* สีม่วง */
        color: white !important;
        font-size: 16px;
        font-weight: bold;
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        display: inline-block;
        cursor: pointer;
        text-align: center;

    }

    /* เมื่อเมาส์ไปชี้ที่ปุ่ม Close */
    .btn-close-modal:hover {
        transform: scale(1.1);
        opacity: 0.9;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        background-color: #d32f2f;
    }

    /* เอฟเฟกต์ตอนกดปุ่ม Close */
    .btn-close-modal:active {
        transform: scale(0.95);
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.15);
        background-color: #a52020;
    }
    /* ปุ่มใน Dark Mode */
    body.dark-mode .btn-close-modal {
        background-color:#A52A2A !important; /* สีม่วงหม่น */
        border-color: #A52A2A !important; /* สีเส้นขอบ */
        color: white !important; /* สีตัวอักษร */
    }

    /* Hover ใน Dark Mode */
    body.dark-mode .btn-close-modal:hover {
        background-color: #8c2424 !important; /* สีม่วงหม่น */
    }
</style>
<body class="nav-fixed">
<?php require 'pp-setting-sidenavAccordion.php'; ?>
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
                <div class="card mb-4 w-200" id="table-machine">
                    <div class="card-header bg-red fw-bold text-white fs-4 d-flex justify-content-between">
                        <div>Machines List Info</div>
<!--                        <div>-->
<!--                            <span id="hours"></span> :-->
<!--                            <span id="minutes"></span> :-->
<!--                            <span id="seconds"></span>-->
<!--                        </div>-->
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple" class="table table-striped">
                            <thead class="text-black" style="background-color: #ffea07">
                            <?php require 'pp-machine-info-table-head.php' ?>
                            </thead>
                            <tbody><?php require "pp-machine-info-table.php"; ?></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<div class="modal fade" id="setting_mc_modal" tabindex="-1" role="dialog" aria-labelledby="setting_mc_modal_label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="setting_mc_modal_label">Machine Setup</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <!-- ID MC -->
                    <div class="col-md-12 d-flex">
                        <label class="small fw-bold me-3" for="modal_id_mc" style="width: 200px;">ID MC</label>
                        <div id="modal_id_mc" class="form-control" readonly></div>
                    </div>

                    <!-- Machine Type -->
                    <div class="col-md-12 d-flex">
                        <label class="small fw-bold me-3" for="modal_id_mc_type" style="width: 200px;">Machine Type</label>
                        <select id="modal_id_mc_type" name="modal_id_mc_type" class="form-control">
                            <option></option>
                            <?php
                            require 'update/establish.php';
                            $sql = "SELECT id_mc_type, mc_type FROM machine_type";
                            $result = $conn->query($sql);
                            require 'update/terminate.php';

                            while ($row = $result->fetch_assoc()) {
                                $selected = ($row['id_mc_type'] == $data_machine['id_mc_type']) ? 'selected' : '';
                                echo "<option value='" . $row["id_mc_type"] . "' $selected>" . $row["mc_type"] . "</option>";
                            }
                            ?>
                        </select>
                    </div>


                    <!-- Description MC -->
                    <div class="col-md-12 d-flex">
                        <label class="small fw-bold me-3" for="modal_mc_des" style="width: 200px;">Description MC</label>
                        <input class="form-control" id="modal_mc_des" name="modal_mc_des" type="text">
                    </div>

                    <!-- ID CAMERA -->
                    <div class="col-md-12 d-flex">
                        <label class="small fw-bold me-3" for="modal_id_cam" style="width: 200px;">ID Camera</label>
                        <input class="form-control" id="modal_id_cam" name="modal_id_cam" type="text">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-close-modal" data-bs-dismiss="modal">Close</button>
                <button type="button" id="modal_button_save" class="btn btn-confirm-modal">Confirm</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="delete_mc_modal" tabindex="-1" role="dialog" aria-labelledby="delete_mc_modal_label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delete_mc_modal_label">Machine Delete</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="modal_table" class="table table-striped">
                    <tr>
                        <td>Confirm delete Machine Id: </td>
                        <td id="modal_delete_machine_id"></td>
                        <td></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn  btn-close-modal" data-bs-dismiss="modal">Close</button>
                <button type="button" id="modal_button_delete" class="btn btn-confirm-modal">Delete</button>
            </div>
        </div>
    </div>
</div>

<script src="js/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
<script src="js/simple-datatables@latest" type="text/javascript"></script>
<script src="js/datatables/datatables-staff.js"></script>
<script src="js/datatables/datatables-simple-demo.js"></script>
<script src="js/litepicker/dist/bundle.js"></script>
<script src="js/litepicker.js"></script>
<script src="js/majorette/pp-time-stamp.js"></script> <!-- อ้างอิงไฟล์ pp-time-stamp.js -->
<script type="text/javascript" src="js/majorette/pp-session.js"></script>

</body>
</html>
