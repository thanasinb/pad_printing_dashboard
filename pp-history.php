<?php
require 'pp-session-start.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>History List</title>
    <link href="css/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/litepicker/dist/css/litepicker.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/file.png" />
    <script data-search-pseudo-elements defer src="js/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="js/feather-icons/4.28.0/feather.min.js"></script>
    <link rel="stylesheet" href="css/reorder-columns/dragtable.css">
    <link rel="stylesheet" href="css/reorder-columns/bootstrap-table.min.css">
    <link rel="stylesheet" href="css/majorette.css">
    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/jquery/jquery-ui.min.js"></script>

    <script type="text/javascript" src="js/majorette/pp-session.js"></script>



    <!--    <link rel="stylesheet" href="css/history-table.css">-->

</head>
<style>
    /* ปรับแต่งสไตล์ Dropdown ให้เรียบง่าย */
    #filter-action {
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #fff;
        font-size: 16px;
        font-family: Arial, sans-serif;
    }

    #filter-action:focus {
        border-color: #007bff;
        outline: none;
    }

    label {
        font-family: Arial, sans-serif;
        font-size: 16px;
        font-weight: normal;
        color: gray;
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
                        <div>History List </div>
                        <div>
                            <span id="hours"></span> :
                            <span id="minutes"></span> :
                            <span id="seconds"></span>
                        </div>
                    </div>

                    <div class="modal fade" id="historyTableModal" tabindex="-1" aria-labelledby="historyTableModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="historyTableModalLabel">History Table</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-striped table-bordered text-center">
                                        <thead>
                                        <tr>
                                            <th><i class="me-2 text-green" data-feather="list"></i>List</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td><i class="me-2 text-green" data-feather="log-in"></i> Login</td>
                                        </tr>
                                        <tr>
                                            <td><i class="me-2 text-red" data-feather="log-out"></i>Logout (Log out yourself)</td>
                                        </tr>
                                        <tr>
                                            <td><i class="me-2 text-gray" data-feather="log-out"></i>Logout (session expired)</td>
                                        </tr>
                                        <tr>
                                            <td><i class="me-2 text-blue" data-feather="download"></i> Download QR Code</td>
                                        </tr>
                                        <tr>
                                            <td><i class="me-2 text-blue" data-feather="edit"></i> แก้ไข Username ของพนักงาน</td>
                                        </tr>
                                        <tr>
                                            <td><i class="me-2 text-blue" data-feather="edit"></i> แก้ไข Password ของพนักงาน</td>
                                        </tr>
                                        <tr>
                                            <td><i class="me-2 text-blue" data-feather="save"></i> บันทึก QR CODE</td>
                                        </tr>
                                        <tr>
                                            <td><i class="me-2 text-blue" data-feather="edit"></i> แก้ไข Description ใน Downtime</td>
                                        </tr>
                                        <tr>
                                            <td><i class="me-2 text-blue" data-feather="edit"></i> แก้ไข Downtime Code</td>
                                        </tr>
                                        <tr>
                                            <td><i class="me-2 text-green" data-feather="plus-circle"></i> เพิ่ม Downtime</td>
                                        </tr>
                                        <tr>
                                            <td><i class="me-2 text-red" data-feather="minus-circle"></i> ลบ Downtime</td>
                                        </tr>

                                        <tr>
                                            <td><i class="me-2 text-green" data-feather="plus-circle"></i> เพิ่มผู้ใช้</td>
                                        </tr>
                                        <tr>
                                            <td><i class="me-2 text-red" data-feather="minus-circle"></i> ลบผู้ใช้</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form id="filterForm" method="GET" action="" style="display: flex; align-items: center; gap: 20px; margin-bottom: 20px;">
                            <label for="filter-action" style="font-size: 16px; font-weight: normal; color: gray;">
                                <i class="me-2 text-green" data-feather="list"></i>Filter Action:
                            </label>
                            <select name="filter_action" id="filter-action" class="form-control" style="width: auto; font-size: 16px; padding: 5px; border-radius: 4px; border: 1px solid #ccc;">
                                <option value="">All Actions</option>
                                <option value="Login" data-icon="log-in">🔐 Login</option>
                                <option value="Logout (Log out yourself)" data-icon="log-out">🔓 Logout (Log out yourself)</option>
                                <option value="Logout (session expired)" data-icon="log-out">⌛ Logout (session expired)</option>
                                <option value="Download QR Code" data-icon="download">⬇️ Download QR Code</option>
                                <option value="แก้ไข Password และ Username" data-icon="edit">✏️ แก้ไข Password และ Username</option>
                                <option value="บันทึก QR Code" data-icon="save">💾 บันทึก QR Code</option>
                                <option value="แก้ไข Description" data-icon="edit">✏️ แก้ไข Description</option>
                                <option value="แก้ไข Downtime Code" data-icon="edit">✏️ แก้ไข Downtime Code</option>
                                <option value="เพิ่ม Downtime" data-icon="plus-circle">➕ เพิ่ม Downtime</option>
                                <option value="ลบ Downtime" data-icon="minus-circle">➖ ลบ Downtime</option>
                                <option value="เพิ่มผู้ใช้" data-icon="plus-circle">➕ เพิ่ม User</option>
                                <option value="ลบผู้ใช้" data-icon="minus-circle">➖ ลบ User</option>
                            </select>
                        </form>

                        <table id="datatablesSimple" class="table table-striped" style="width: 100%; white-space: nowrap">
                            <thead class="text-black" style="background-color: #ffea07">

                            <?php require_once 'pp-history-table-head.php'; ?>

                            </thead>
                            <tbody id="table_body">

                            <?php require 'pp-history-script.php'; ?>
                            </tbody>

                        </table>
                    </div>
                    <button id="showHistoryTableBtn" class="btn btn-primary btn-apple-style">Show History Table</button>

                </div>
            </div>
        </main>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const filterAction = urlParams.get('filter_action');
        const selectElement = document.getElementById('filter-action');

        if (filterAction) {
            selectElement.value = filterAction; // ตั้งค่า value ของ <select>
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectElement = document.getElementById('filter-action');
        const filterForm = document.getElementById('filterForm');

        // เมื่อเปลี่ยนค่าตัวเลือกใน <select> ให้ส่งฟอร์มทันที
        selectElement.addEventListener('change', function () {
            filterForm.submit();
        });
    });
</script>
<script>
    document.getElementById('showHistoryTableBtn').addEventListener('click', function () {
        const historyTableModal = new bootstrap.Modal(document.getElementById('historyTableModal'));
        historyTableModal.show();
    });

</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterAction = document.getElementById('filter-action');

        Array.from(filterAction.options).forEach(option => {
            const iconType = option.getAttribute('data-icon');
            if (iconType) {
                option.textContent = ` ${option.textContent}`; // เพิ่มไอคอนหน้าข้อความ
            }
        });
    });
</script>
<script src="js/majorette/pp-time-stamp.js"></script>
<script src="js/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
<script src="js/simple-datatables@latest" type="text/javascript"></script>
<script src="js/datatables/datatables-staff.js"></script>
<script src="js/litepicker/dist/bundle.js"></script>
<script src="js/litepicker.js"></script>

</body>
</html>