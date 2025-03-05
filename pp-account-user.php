<?php
require 'pp-session-start.php'
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        #id_staff_suggestions {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ccc;
            background-color: #fff;
        }

        .suggestion-item {
            padding: 8px;
            cursor: pointer;
        }

        .suggestion-item:hover {
            background-color: #f0f0f0;
        }

    </style>
    <!-- Meta Tags -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>User Account List</title>

    <!-- ✅ CSS Stylesheets (โหลดลำดับให้เหมาะสม) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"> <!-- ✅ FontAwesome (ใช้ 6.4.2) -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css"> <!-- ✅ jQuery UI -->
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.css" rel="stylesheet" /> <!-- ✅ Simple DataTables (ระบุเวอร์ชันแน่นอน) -->
    <link href="css/styles.css" rel="stylesheet" /> <!-- ✅ Custom Styles -->
    <link rel="stylesheet" href="css/majorette.css">
    <link href="css/pp-account-user.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/pp-sidenav.css">

    <!-- ✅ JavaScript (เรียงลำดับให้เหมาะสม) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- ✅ โหลด jQuery ก่อน -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script> <!-- ✅ jQuery UI -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous"></script> <!-- ✅ FontAwesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" crossorigin="anonymous"></script> <!-- ✅ Feather Icons -->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script> <!-- ✅ Simple DataTables -->

    <!-- ✅ Custom Scripts (โหลดหลังจาก jQuery & Plugins) -->
    <script type="text/javascript" src="js/majorette/pp-setting-dt.js"></script>
    <script type="text/javascript" src="js/majorette/pp-account-user.js"></script>
    <script src="js/majorette/pp-session.js"></script>


</head>
<style>






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
            <div class="container-fluid px-4 mt-n10">
                <!-- Example DataTable for Dashboard Demo-->
                <div class="card mb-4 w-100" id="table-machine">
                    <div class="card-header bg-red fw-bold text-white fs-4 d-flex justify-content-between">
                        <div>User Account List</div>
<!--                        <div>-->
<!--                            <span id="hours"></span> :-->
<!--                            <span id="minutes"></span> :-->
<!--                            <span id="seconds"></span>-->
<!--                        </div>-->
                    </div>


                    <div class="card-body">
                        <table id="datatablesSimple" class="table table-striped" style="width: 100%; white-space: nowrap">
                            <thead class="text-black" style="background-color: #ffea07">
                            <?php
                            // เรียกใช้ไฟล์ pp-setting-qr-table-head.php เพื่อแสดงหัวตาราง
                            require_once 'pp-account-user-table-head.php';
                            ?>
                            </thead>
                            <tbody id="table_body">

                            <?php
                            require 'pp-account-user-script.php';
                            ?>
                            </tbody>

                        </table>
                        <div class="modal fade" id="setting_dt_modal" tabindex="-1" role="dialog" aria-labelledby="setting_dt_modal_label" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="setting_dt_modal_label">User Edit</h5>
                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <table id="modal_table" class="table table-striped">
                                            <tr>
                                                <td>Id staff: </td>
                                                <td>
                                                    <input type="text" id="modal_id_staff" name="modal_id_staff" readonly class="readonly-input">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>First name: </td>
                                                <td id="modal_first_name"></td>
                                            </tr>
                                            <tr>
                                                <td>Last name: </td>
                                                <td id="modal_last_name"></td>
                                            </tr>
                                            <tr>
                                                <td>Username: </td>
                                                <td>
                                                    <input type="text" id="modal_username" name="modal_username">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Password: </td>
                                                <td>
                                                    <div style="position: relative; display: flex; align-items: center;">
                                                        <input type="password" id="modal_password" name="modal_password" class="form-control" placeholder="Please enter new password">
                                                        <button type="button" id="togglePassword" style="background: none; border: none; margin-left: 5px;">
                                                            <i class="fa fa-eye-slash"></i> <!-- ไอคอนเริ่มต้นเป็นปิดตา -->
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                          






                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-close-modal" data-bs-dismiss="modal">Close</button>
                                        <button type="button" id="modal_button_confirm" class="btn btn-confirm-modal">Confirm</button>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal for deleting user -->
                        <div class="modal fade" id="delete_user_modal" tabindex="-1" role="dialog" aria-labelledby="delete_user_modal_label" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="delete_user_modal_label">User Delete</h5>
                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <table id="modal_table" class="table table-striped">
                                            <tr>
                                                <td>Confirm delete User of Id staff: </td>
                                                <td id="modal_delete_user"></td>
                                                <td></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-close-modal" data-bs-dismiss="modal">Close</button>
                                        <button type="button" id="modal_button_delete" class="btn btn-confirm-modal">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <script src="js/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
<script src="js/simple-datatables@latest" type="text/javascript"></script>
<script src="js/datatables/datatables-staff.js"></script>
<script src="js/litepicker/dist/bundle.js"></script>
<script src="js/litepicker.js"></script>
                      <script  src="js/majorette/pp-time-stamp.js"></script>


</body>
</html>
