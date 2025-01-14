<?php
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
    <title>User Account List</title>
    <link href="css/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/litepicker/dist/css/litepicker.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/profile-user.png" />
    <script data-search-pseudo-elements defer src="js/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="js/feather-icons/4.28.0/feather.min.js"></script>
    <link rel="stylesheet" href="css/majorette.css">
    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/majorette/pp-setting-dt.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" crossorigin="anonymous"></script>
    <!-- js script -->
    <script type="text/javascript" src="js/majorette/pp-account-user.js"></script>
    <!-- css style -->
    <link href="css/pp-account-user.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/pp-sidenav.css">

    <!--        <script src="js/reorder-columns/jquery.dragtable.js"></script>-->
    <!--        <script src="js/reorder-columns/bootstrap-table.min.js"></script>-->
    <!--        <script src="js/reorder-columns/bootstrap-table-reorder-columns.js"></script>-->
    <!--        <script src="js/majorette/pp-dragtable.js"></script>-->
    <!--        <script type="text/javascript" src="js/datetimepicker4/moment.min.js"></script>-->
    <!--        <script type="text/javascript" src="js/datetimepicker4/tempusdominus-bootstrap-4.min.js"></script>-->
    <!--        <link rel="stylesheet" href="css/datetimepicker4/tempusdominus-bootstrap-4.min.css" />-->
    <!--        <script type="text/javascript" src="js/majorette/pp-machine-currentTaskModal.js"></script>-->
    <!--        <script type="text/javascript" src="js/majorette/pp-machine-refresh.js"></script>-->

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
</head>
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
                        <div>User List</div>
                        <div>
                            <span id="hours"></span> :
                            <span id="minutes"></span> :
                            <span id="seconds"></span>
                        </div>
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
                                        <h5 class="modal-title" id="setting_dt_modal_label">User</h5>
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
                                                            <i class="fas fa-eye eye-icon"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" id="modal_button_confirm" class="btn btn-primary">Confirm</button>
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
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" id="modal_button_delete" class="btn btn-primary">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add User Modal -->
                        <div class="modal fade" id="add_user_modal" tabindex="-1" role="dialog" aria-labelledby="add_user_modal_label" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="add_user_modal_label">Add New User</h5>
                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="add_user_form">
                                            <div class="mb-3">
                                                <label for="new_id_staff" class="form-label">Staff ID</label>
                                                <input type="text" class="form-control" id="new_id_staff" name="new_id_staff" autocomplete="off">
                                                <div id="id_staff_suggestions" class="list-group" style="position: absolute; z-index: 10;"></div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="preview_name" class="form-label">Name Preview</label>
                                                <input type="text" id="preview_name" class="form-control" placeholder="First Name+Last Name" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="new_username" class="form-label">Username</label>
                                                <input type="text" class="form-control" id="new_username" name="new_username" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="new_password" class="form-label">Password</label>
                                                <div style="display: flex; align-items: center;">
                                                    <input type="password" class="form-control" id="new_password" name="new_password" required style="flex: 1; margin-right: 5px;">
                                                    <button type="button" id="togglePasswordAdd" style="background: none; border: none; cursor: pointer;">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-primary">Add User</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                const togglePasswordAdd = document.getElementById("togglePasswordAdd");
                                const passwordFieldAdd = document.getElementById("new_password");

                                togglePasswordAdd.addEventListener("click", function () {
                                    // สลับ type ระหว่าง password และ text
                                    const type = passwordFieldAdd.getAttribute("type") === "password" ? "text" : "password";
                                    passwordFieldAdd.setAttribute("type", type);

                                    // สลับไอคอน
                                    this.innerHTML = type === "password"
                                        ? '<i class="fas fa-eye"></i>'
                                        : '<i class="fas fa-eye-slash"></i>';
                                });
                            });
                        </script>
                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                const togglePassword = document.getElementById("togglePassword");
                                const passwordField = document.getElementById("modal_password");
                                const placeholderText = document.getElementById("hidden_placeholder");
                                const confirmButton = document.querySelector("#modal_button_confirm");

                                // สลับการแสดง/ซ่อนรหัสผ่าน
                                togglePassword.addEventListener("click", function () {
                                    const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
                                    passwordField.setAttribute("type", type);
                                    this.innerHTML = type === "password"
                                        ? '<i class="fas fa-eye"></i>'
                                        : '<i class="fas fa-eye-slash"></i>';
                                });

                                // ซ่อน placeholder เมื่อเริ่มพิมพ์
                                passwordField.addEventListener("input", function () {
                                    placeholderText.style.display = passwordField.value.length > 0 ? "none" : "block";
                                });

                                // ตรวจสอบก่อนกด Confirm
                                confirmButton.addEventListener("click", function (event) {
                                    if (passwordField.value.trim() === "") {
                                        alert("กรุณาใส่รหัสผ่านใหม่ก่อนกด Confirm!");
                                        event.preventDefault(); // ป้องกันการส่งฟอร์ม
                                    }
                                });
                            });
                        </script>
                        <script>

                            document.addEventListener('click', () => checkSession());
                            document.addEventListener('input', () => checkSession());
                            </script>
                            <script>
                                $(document).ready(function() {
                                    $('#new_id_staff').on('input', function() {
                                        var staffId = $(this).val();
                                        if (staffId.trim() !== "") {
                                            $.ajax({
                                                url: 'get-staff-details.php', // ไฟล์ PHP สำหรับดึงข้อมูล staff
                                                type: 'POST',
                                                data: { id_staff: staffId },
                                                success: function(response) {
                                                    var data = JSON.parse(response);
                                                    if (data.statusCode === 200) {
                                                        $('#preview_name').val(data.name_first + ' ' + data.name_last);
                                                    } else {
                                                        $('#preview_name').val('No staff found');
                                                    }
                                                },
                                                error: function() {
                                                    $('#preview_name').val('Error fetching staff details');
                                                }
                                            });
                                        } else {
                                            $('#preview_name').val(''); // รีเซ็ตหากไม่มีค่า
                                        }
                                    });
                                });

                        </script>
                        <script>
                            $('#add_user_form').on('submit', function(event) {
                                event.preventDefault();

                                $.ajax({
                                    url: 'pp-account-user-add.php',
                                    method: 'POST',
                                    data: $(this).serialize(),
                                    success: function(response) {
                                        if (response.statusCode === 200) {
                                            alert('User added successfully');
                                            location.reload();
                                        } else {
                                            alert('Error: ' + response.message);
                                        }
                                    },
                                    error: function() {
                                        alert('Error occurred while adding user.');
                                    }
                                });
                            });
                        </script>
                        <script>
                            $(document).ready(function() {
                                $('#new_id_staff').on('input', function() {
                                    var query = $(this).val();

                                    if (query.length > 0) {
                                        $.ajax({
                                            url: 'fetch-staff-id.php',
                                            method: 'POST',
                                            data: { query: query },
                                            success: function(data) {
                                                $('#id_staff_suggestions').html(data);
                                            }
                                        });
                                    } else {
                                        $('#id_staff_suggestions').html('');
                                    }
                                });

                                // เมื่อคลิกเลือก Staff ID
                                $(document).on('click', '.suggestion-item', function() {
                                    var staffId = $(this).text();
                                    $('#new_id_staff').val(staffId);
                                    $('#id_staff_suggestions').html('');

                                    // ดึงข้อมูลชื่อจริงและนามสกุล
                                    $.ajax({
                                        url: 'fetch-staff-details.php', // ไฟล์ PHP ดึงข้อมูล Staff ตาม ID
                                        method: 'POST',
                                        data: { id_staff: staffId },
                                        success: function(response) {
                                            var data = JSON.parse(response);
                                            if (data.statusCode === 200) {
                                                $('#preview_name').val(data.name_first + ' ' + data.name_last);
                                            } else {
                                                $('#preview_name').val('Name not found');
                                            }
                                        }
                                    });
                                });
                            });
                        </script>
                        <script src="js/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
<script src="js/simple-datatables@latest" type="text/javascript"></script>
<script src="js/datatables/datatables-staff.js"></script>
<script src="js/litepicker/dist/bundle.js"></script>
<script src="js/litepicker.js"></script>
                      <script  src="js/majorette/pp-time-stamp.js"></script>
<!--                        <script type="text/javascript" src="js/majorette/pp-session.js"></script>-->


</body>
</html>
