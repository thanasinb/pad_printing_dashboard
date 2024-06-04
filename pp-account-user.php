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
    <title>User List</title>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- js script -->
    <script type="text/javascript" src="js/majorette/pp-account-user.js"></script>
    <!-- css style -->
    <link href="css/pp-account-user.css" rel="stylesheet" />
    <!--        <script src="js/reorder-columns/jquery.dragtable.js"></script>-->
    <!--        <script src="js/reorder-columns/bootstrap-table.min.js"></script>-->
    <!--        <script src="js/reorder-columns/bootstrap-table-reorder-columns.js"></script>-->
    <!--        <script src="js/majorette/pp-dragtable.js"></script>-->
    <!--        <script type="text/javascript" src="js/datetimepicker4/moment.min.js"></script>-->
    <!--        <script type="text/javascript" src="js/datetimepicker4/tempusdominus-bootstrap-4.min.js"></script>-->
    <!--        <link rel="stylesheet" href="css/datetimepicker4/tempusdominus-bootstrap-4.min.css" />-->
    <!--        <script type="text/javascript" src="js/majorette/pp-machine-currentTaskModal.js"></script>-->
    <!--        <script type="text/javascript" src="js/majorette/pp-machine-refresh.js"></script>-->
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
                                                    <input type="text" id="modal_password" name="modal_password">
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
                                                <input type="text" class="form-control" id="new_id_staff" name="new_id_staff" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="new_username" class="form-label">Username</label>
                                                <input type="text" class="form-control" id="new_username" name="new_username" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="new_password" class="form-label">Password</label>
                                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Add User</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <script>
                            $(document).ready(function(){
                                $('#add_user_form').on('submit', function(event){
                                    event.preventDefault();

                                    $.ajax({
                                        url: 'pp-account-user-add.php', // เปลี่ยนเส้นทางไปยังไฟล์ php ที่ทำงานเพื่อบันทึกข้อมูล
                                        method: 'POST',
                                        data: $(this).serialize(),
                                        success: function(data){
                                            $('#add_user_modal').modal('hide');
                                            location.reload(); // รีโหลดหน้าเพื่อดูผลลัพธ์
                                        },
                                        error: function(){
                                            alert('Error adding user');
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
                        <script type="text/javascript" src="js/majorette/pp-session.js"></script>

</body>
</html>
