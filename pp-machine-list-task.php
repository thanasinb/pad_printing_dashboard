<?php
//require 'pp-job-add-script.php';
require 'pp-session-start.php'
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>List Task</title>
    <link href="css/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/litepicker/dist/css/litepicker.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/employee.png" />
    <script data-search-pseudo-elements defer src="js/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="js/feather-icons/4.28.0/feather.min.js"></script>
<!--    <link rel="stylesheet" href="css/reorder-columns/dragtable.css">-->
<!--    <link rel="stylesheet" href="css/reorder-columns/bootstrap-table.min.css">-->
    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/jquery/jquery-ui.min.js"></script>
<!--    <script src="js/reorder-columns/jquery.dragtable.js"></script>-->
<!--    <script src="js/reorder-columns/bootstrap-table.min.js"></script>-->
<!--    <script src="js/reorder-columns/bootstrap-table-reorder-columns.js"></script>-->
    <script src="js/majorette/pp-dragtable.js"></script>
    <script src="js/majorette/pp-machine-list-task.js"></script>
    <link rel="stylesheet" href="css/pp-sidenav.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="path/to/jquery.dragtable.js"></script>
    <script type="text/javascript" src="js/majorette/pp-setting-job-list.js"></script>

</head>
<style>
    /* เอฟเฟกต์ปุ่ม Confirm (สีน้ำเงิน) */
    .form-button-submit {
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
    .form-button-submit:hover {
        transform: scale(1.1);
        opacity: 0.9;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        background-color: #0069d9; /* สีน้ำเงินเข้มขึ้น */
    }

    /* เอฟเฟกต์ตอนกดปุ่ม Confirm */
    .form-button-submit:active {
        transform: scale(0.95);
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.15);
        background-color: #0056b3;
    }


    /* ปุ่มใน Dark Mode */
    body.dark-mode .form-button-submit {
        background-color: #375a7f !important; /* สีฟ้าหม่น */
        color: #ffffff !important; /* สีข้อความขาว */
        border: 1px solid #444444 !important; /* เส้นขอบเข้ม */
    }
    /* Hover ใน Dark Mode */
    body.dark-mode .form-button-submit:hover {
        background-color: #3b566a !important; /* สีฟ้าหม่นเข้มขึ้นเมื่อ hover */
    }

    /* เอฟเฟกต์ปุ่ม Close (สีม่วง) */
    .form-button-reset {
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
    .form-button-reset:hover {
        transform: scale(1.1);
        opacity: 0.9;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        background-color: #d32f2f;
    }

    /* เอฟเฟกต์ตอนกดปุ่ม Close */
    .form-button-reset:active {
        transform: scale(0.95);
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.15);
        background-color: #a52020;
    }
    /* ปุ่มใน Dark Mode */
    body.dark-mode .form-button-reset {
        background-color:#A52A2A !important; /* สีม่วงหม่น */
        border-color: #A52A2A !important; /* สีเส้นขอบ */
        color: white !important; /* สีตัวอักษร */
    }

    /* Hover ใน Dark Mode */
    body.dark-mode .form-button-reset:hover {
        background-color: #8c2424 !important; /* สีม่วงหม่น */
    }
</style>
<body class="nav-fixed">
<?php
require 'pp-planning-sidenavAccordion.php';
?>
<div id="layoutSidenav">
    <?php
    require 'pp-layoutSidenav_nav.php';
    ?>
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
                <div class="col-xl-20">
                    <div class="card mb-6">
                        <div class="card-header bg-red fw-bold text-white">Manufacturing orders to be assigned to Machine: <?php echo $_POST["id_mc"]; ?></div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="table table-striped">
                                <thead class="table-dark">
                                <?php require 'pp-machine-list-task-table-head.php' ?>
                                </thead>

                                <tbody>
                                <?php require 'pp-machine-list-task-script.php'; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<div class="modal fade" id="setting_job_modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Job</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Job ID: <span id="modal_id_job"></span></p>
                <label>Operation:</label>
                <input type="text" id="modal_job_operation" class="form-control">
                <label>Machine:</label>
                <input type="text" id="modal_job_machine" class="form-control">
                <label>Work Order:</label>
                <input type="text" id="modal_job_workorder" class="form-control">
                <label>Item No:</label>
                <input type="text" id="modal_job_item" class="form-control">
                <label>Color:</label>
                <input type="text" id="modal_job_color" class="form-control">
                <label>Side:</label>
                <input type="text" id="modal_job_side" class="form-control">
                <label>Due Date:</label>
                <input type="text" id="modal_job_due" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn form-button-reset" data-bs-dismiss="modal">Close</button>
                <button type="button" id="modal_button_save" class="btn form-button-submit">Confirm</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="job_info_delete" tabindex="-1" role="dialog" aria-labelledby="delete_job_modal_label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delete_job_modal_label">Job Delete</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="modal_table" class="table table-striped">
                    <tr>
                        <td><strong>Job ID:</strong></td>
                        <td id="modal_delete_job_id"></td>
                    </tr>
                    <tr>
                        <td><strong>Operation:</strong></td>
                        <td id="modal_delete_operation"></td>
                    </tr>
                    <tr>
                        <td><strong>Machine:</strong></td>
                        <td id="modal_delete_machine"></td>
                    </tr>
                    <tr>
                        <td><strong>Date Due:</strong></td>
                        <td id="modal_delete_date_due"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn form-button-reset" data-bs-dismiss="modal">Close</button>
                <button type="button" id="modal_button_delete_job" class="btn form-button-submit">Delete </button>
            </div>
        </div>
    </div>
</div>




<script src="js/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
<!--<script src="js/Chart.js/2.9.4/Chart.min.js"></script>-->
<!--<script src="assets/demo/chart-area-demo.js"></script>-->
<!--<script src="assets/demo/chart-bar-demo.js"></script>-->
<script src="js/simple-datatables@latest" type="text/javascript"></script>
<script src="js/datatables/datatables-machine-list-task.js"></script>
<script src="js/litepicker/dist/bundle.js"></script>
<script src="js/litepicker.js"></script>
</body>
</html>
