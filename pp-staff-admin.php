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
    <title>Staff List</title>
    <link href="css/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/litepicker/dist/css/litepicker.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/multiple-users-silhouette.png" />
    <script data-search-pseudo-elements defer src="js/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="js/feather-icons/4.28.0/feather.min.js"></script>
    <link rel="stylesheet" href="css/reorder-columns/dragtable.css">
    <link rel="stylesheet" href="css/reorder-columns/bootstrap-table.min.css">
    <link rel="stylesheet" href="css/majorette.css">
    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/jquery/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="css/pp-sidenav.css">

    <script type="text/javascript" src="js/majorette/pp-machine-staff.js"></script>

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
                <div class="card mb-4 w-100">
                    <div class="card-header bg-red fw-bold text-white fs-4">Admins List</div>
                    <div class="card-body">
                        <table id="datatablesSimple" class="table table-striped">
                            <thead class="text-black" style="background-color: #ffea07"><?php require 'pp-staff-table-head.php' ?></thead>
                            <tbody><?php require "pp-staff-list-staff-script-admin.php"; ?></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // ดึงค่าพารามิเตอร์ role จาก URL
        const urlParams = new URLSearchParams(window.location.search);
        const selectedRole = urlParams.get('role');

        if (selectedRole) {
            // หาทุกแถวที่มี data-role ตรงกับ selectedRole โดยตรง
            document.querySelectorAll(`tr[data-role="${selectedRole}"]`).forEach(row => {
                row.classList.add('highlight');
            });

            // ลบพารามิเตอร์ role ออกจาก URL เพื่อให้ refresh แล้ว highlight หาย
            const newUrl = window.location.pathname; // เอาเฉพาะ path โดยไม่เอา query string
            window.history.replaceState({}, '', newUrl); // เปลี่ยน URL โดยไม่ reload หน้า
        }
    });
</script>
<div class="modal fade" id="staff_modal" tabindex="-1" role="dialog" aria-labelledby="staff_modal_label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staff_modal_label">Staff Edit</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span id="modal_span_staff_id"></span>
                <table id="modal_table" class="table table-striped">
                    <tr>
                        <td>Staff ID</td>
                        <td id="modal_staff_id"><input type="text" id="input_staff_id" name="input_staff_id" readonly></td>
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
                        <td id="modal_site"><input type="text" id="input_site" name="input_site" disabled></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Role</td>
                        <td > <form>
                                <select name="role" id="role"disabled>

                                    <option value="1">Operator</option>
                                    <option value="2">Technician</option>
                                    <option value="3">Production Support</option>
                                    <option value="4">Instructor</option>
                                    <option value="5">Senior Instructor</option>
                                    <option value="6">Foreman</option>
                                    <option value="7">Leader</option>
                                    <option value="8">Senior Technician</option>
                                    <option value="9">Manager</option>
                                    <option value="10">Engineering</option>
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
            <div class="modal-footer justify-content-between">
                <button id="button_rfid" class="btn btn-confirm-modal mr-auto" type="button">Change</button>
                <button id="button_save_rfid" class="btn btn-confirm-modal mr-auto" type="button">Save</button>
                <button class="btn btn-close-modal" type="button" data-bs-dismiss="modal">Close</button>
                <!--                    <button class="btn btn-primary" type="button">Save changes</button>-->
            </div>
        </div>
    </div>
</div>
<!-- Modal for deleting user -->
<div class="modal fade" id="delete_user_modal" tabindex="-1" role="dialog" aria-labelledby="delete_user_modal_label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delete_user_modal_label">Staff Delete</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="modal_table" class="table table-striped">
                    <tr>
                        <td>Confirm delete Staff of Id staff: </td>
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
<script type="text/javascript" src="js/majorette/pp-session.js"></script>

</body>
</html>
