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
    <title>Add New Staff</title>
    <link href="css/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/litepicker/dist/css/litepicker.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/multiple-users-silhouette.png" />
    <script data-search-pseudo-elements defer src="js/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="js/feather-icons/4.28.0/feather.min.js"></script>
    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/jquery/jquery-ui.min.js"></script>
    <script src="js/majorette/pp-staff-add-validate-input.js"></script>
</head>
<style>
    body {
        background-color: #f8f9fa;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        font-size: 1.25rem;
        font-weight: bold;
        background: linear-gradient(135deg,  #f44336, #f44336);
        color: white !important;
        border-radius: 12px 12px 0 0;
        text-align: center;
        padding: 15px;
    }

    .form-control, .form-select {
        border-radius: 8px;
        padding: 10px;
        font-size: 1rem;
    }

    .btn-confirm {
        background: #007bff;
        color: white;
        font-weight: bold;
        padding: 12px;
        width: 100%;
        border-radius: 8px;
        transition: all 0.3s ease-in-out;
    }

    .btn-confirm:hover {
        background: #0056b3;
        transform: scale(1.05);
    }

    /* Profile Image */
    .profile-container {
        text-align: center;
        margin-bottom: 20px;
    }

    .profile-container img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #007bff;
    }
</style>
<body class="nav-fixed">
<?php require 'pp-staff-sidenavAccordion.php'; ?>
<div id="layoutSidenav">
    <?php require 'pp-layoutSidenav_nav.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Add New Staff</div>
                        <div class="card-body">
                            <form method="post" action="pp-staff-add-action.php" enctype="multipart/form-data">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="id_staff" class="form-label">Staff ID</label>
                                        <input type="text" class="form-control" id="id_staff" name="id_staff" required maxlength="6">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="id_rfid" class="form-label">RFID</label>
                                        <input type="text" class="form-control" id="id_rfid" name="id_rfid" required maxlength="10">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="prefix" class="form-label">Prefix</label>
                                    <select class="form-select" id="prefix" name="prefix" required>
                                        <option value="">Select</option>
                                        <option value="1">นาย</option>
                                        <option value="2">นาง</option>
                                        <option value="3">นางสาว</option>
                                    </select>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="name_first" class="form-label">First name</label>
                                        <input type="text" class="form-control" id="name_first" name="name_first" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="name_last" class="form-label">Last name</label>
                                        <input type="text" class="form-control" id="name_last" name="name_last" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="id_role_group" class="form-label">Role Group</label>
                                        <select class="form-select" id="id_role_group" name="id_role_group" required>
                                            <option value="" selected>Select</option>
                                            <option value="1">Operator</option>
                                            <option value="2">Foreman</option>
                                            <option value="3">Admin</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="id_role" class="form-label">Role</label>
                                        <select class="form-select" id="id_role" name="id_role" required>
                                            <option value="" selected>Select</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="id_shif" class="form-label">Shift</label>
                                        <select class="form-select" id="id_shif" name="id_shif" required>
                                            <option value="">Select</option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="profile-container">
                                    <label class="form-label">Profile Picture</label><br>
                                    <img id="profilePreview" src="assets/img/illustrations/profiles/profile-1.png">
                                    <br>
                                    <input type="file" class="form-control mt-2" name="profileImage" id="profileImage" accept="image/*">
                                </div>

                                <button class="btn btn-confirm" type="submit">Add Staff</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.getElementById('id_role_group').addEventListener('change', function() {
                    const roleGroup = this.value;
                    const roleSelect = document.getElementById('id_role');
                    roleSelect.innerHTML = '<option value="" selected>Select</option>';

                    let options = [];
                    if (roleGroup === '1') {
                        options = [
                            { value: '1', text: 'Operator' },
                            { value: '2', text: 'Technician' },
                            { value: '3', text: 'Production Support' },
                            { value: '4', text: 'Instructor' }
                        ];
                    } else if (roleGroup === '2') {
                        options = [{ value: '6', text: 'Foreman' }];
                    } else if (roleGroup === '3') {
                        options = [
                            { value: '9', text: 'Manager' },
                            { value: '10', text: 'Engineer' }
                        ];
                    }

                    options.forEach(option => {
                        const opt = document.createElement('option');
                        opt.value = option.value;
                        opt.textContent = option.text;
                        roleSelect.appendChild(opt);
                    });
                });

                document.getElementById('profileImage').addEventListener('change', function(event) {
                    const reader = new FileReader();
                    reader.onload = function() {
                        document.getElementById('profilePreview').src = reader.result;
                    };
                    reader.readAsDataURL(event.target.files[0]);
                });
            </script>


<script src="js/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
<script src="js/Chart.js/2.9.4/Chart.min.js"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="js/simple-datatables@latest" type="text/javascript"></script>
<script src="js/datatables/datatables-simple-demo.js"></script>
<script src="js/litepicker/dist/bundle.js"></script>
<script src="js/litepicker.js"></script>
<script type="text/javascript" src="js/majorette/pp-session.js"></script>

</body>
</html>
