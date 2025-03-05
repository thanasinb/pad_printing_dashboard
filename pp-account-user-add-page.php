<?php
require 'pp-session-start.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Add New User" />
    <meta name="author" content="" />
    <title>Add New User</title>
    <link href="css/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/litepicker/dist/css/litepicker.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/multiple-users-silhouette.png" />
    <script src="js/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="js/feather-icons/4.28.0/feather.min.js"></script>
    <script src="js/jquery/jquery.min.js"></script>
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

</style>
<body class="nav-fixed">
<?php require 'pp-staff-sidenavAccordion.php'; ?>
<div id="layoutSidenav">
    <?php require 'pp-layoutSidenav_nav.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <header class="page-header page-header-dark pb-2">
                <div class="container-xl px-4">
                    <div class="page-header-content pt-4">
                    </div>
                </div>
            </header>
            <div class="container-xl d-flex justify-content-center align-items-center px-2 mt-n15" style="height: 100vh;">
                <div class="col-xl-6">
                    <div class="card mb-4">
                        <div class="card-header bg-red text-white">Add New User</div>
                        <div class="card-body">
                            <form id="add_user_form" method="post" action="pp-account-user-add.php">
                                <div class="mb-3">
                                    <label for="new_id_staff" class="form-label">Staff ID</label>
                                    <input type="text" class="form-control" id="new_id_staff" name="new_id_staff" autocomplete="off" required>
                                    <div id="id_staff_suggestions" class="list-group" style="position: absolute; z-index: 10;"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="preview_name" class="form-label">Name Preview</label>
                                    <input type="text" id="preview_name" class="form-control" placeholder="First Name + Last Name" readonly>
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
                                <button type="submit" class="btn btn-confirm-modal">Add User</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
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
            dataType: 'json',  // เพิ่มการกำหนดว่า response เป็น JSON
            success: function(response) {
                if (response.statusCode === 200) {
                    alert('User added successfully');
                    window.location.href = 'pp-account-user.php'; // Direct ไปยังหน้าที่ต้องการ
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
    $(document).ready(function () {
        $('#new_id_staff').on('input', function () {
            var query = $(this).val();

            if (query.length > 0) {
                $.ajax({
                    url: 'fetch-staff-id.php',
                    method: 'POST',
                    data: { query: query },
                    success: function (data) {
                        $('#id_staff_suggestions').html(data);
                    }
                });
            } else {
                $('#id_staff_suggestions').html('');
            }
        });

        // เลือกรายการจากรายการแนะนำ
        $(document).on('click', '.suggestion-item', function () {
            var staffId = $(this).data('id');
            $('#new_id_staff').val(staffId);
            $('#id_staff_suggestions').html('');

            // ดึงข้อมูลชื่อจริงและนามสกุลโดยตรวจสอบ role_group = 2 หรือ 3
            $.ajax({
                url: 'fetch-staff-details.php',
                method: 'POST',
                data: { id_staff: staffId },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.statusCode === 200 && (data.role_group === 2 || data.role_group === 3)) {
                        $('#preview_name').val(data.name_first + ' ' + data.name_last);
                    } else {
                        $('#preview_name').val('Not allowed');
                    }
                }
            });
        });
    });
</script>
<script src="js/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>
