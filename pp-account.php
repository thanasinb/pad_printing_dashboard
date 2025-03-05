<?php
require 'pp-session-start.php';
require 'update/establish.php'; // เชื่อมต่อกับฐานข้อมูล

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username']; // ดึง username จาก session
$role_group_name = $_SESSION['role_group_name'] ?? 'Unknown';
$role = $_SESSION['role'] ?? 'Unknown';

// ดึงข้อมูลพนักงานและภาพโปรไฟล์จากฐานข้อมูล
$sql = "SELECT 
            staff.id_row, 
            staff.id_staff, 
            staff.id_rfid, 
            prefix.prefix AS prefix, 
            staff.name_first, 
            staff.name_last, 
            staff.profile_image 
        FROM staff 
        INNER JOIN login ON staff.id_staff = login.id_staff 
        INNER JOIN prefix ON staff.prefix = prefix.id_prefix 
        WHERE login.username = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$staff = $result->fetch_assoc();
$stmt->close();
require 'update/terminate.php';

// ตรวจสอบว่าพบข้อมูลหรือไม่
if (!$staff) {
    echo "ไม่พบข้อมูลพนักงาน";
    exit();
}

// ตั้งค่าภาพโปรไฟล์
$profileImagePath = !empty($staff['profile_image']) ? "uploads/" . $staff['profile_image'] : "assets/img/illustrations/profiles/profile-1.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body.dark-mode .page-header {
            background-color: #A52A2A !important;  /* พื้นหลังสีน้ำตาลแดง */
            color: white !important;  /* ตัวอักษรสีขาว */
            font-weight: bold;
        }
    </style>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Account</title>
    <link href="css/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/litepicker/dist/css/litepicker.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/profile-user.png" />
    <script data-search-pseudo-elements defer src="js/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="js/feather-icons/4.28.0/feather.min.js"></script>
    <link rel="stylesheet" href="css/reorder-columns/dragtable.css">
    <link rel="stylesheet" href="css/reorder-columns/bootstrap-table.min.css">
    <link rel="stylesheet" href="css/majorette.css">
    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/jquery/jquery-ui.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet" />
    <!-- Cropper.js JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <link rel="stylesheet" href="css/pp-sidenav.css">
    <link rel="stylesheet" href="css/pp-account-profile.css">
    <link rel="stylesheet" href="css/pp-account-form.css">

</head>
<style>
    /* เอฟเฟกต์ปุ่ม Upload Image */
    .upload-image-btn {
        transition: all 0.2s ease-in-out;
        background-color: #28a745; /* สีเขียว */
        color: white;
        font-size: 16px;
        font-weight: bold;
        padding: 12px 20px;
        border-radius: 8px;
        border: none;
        display: inline-block;
        cursor: pointer;
        text-align: center;
    }

    /* เมื่อเมาส์ไปชี้ที่ปุ่ม */
    .upload-image-btn:hover {
        transform: scale(1.1); /* ขยายขึ้นเล็กน้อย */
        opacity: 0.9;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2); /* เพิ่มเงา */
        background-color: #218838; /* สีเขียวเข้มขึ้น */
    }

    /* เอฟเฟกต์ตอนกดปุ่ม */
    .upload-image-btn:active {
        transform: scale(0.95); /* หดลงเล็กน้อย */
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.15); /* ลดเงาลง */
        background-color: #1e7e34; /* สีเข้มขึ้น */
    }

    /* เอฟเฟกต์ปุ่ม Upload */
    .upload-btn {
        transition: all 0.2s ease-in-out;
        background-color: #027bff; /* เขียว */
        color: white;
        font-size: 16px;
        font-weight: bold;
        padding: 12px 20px;
        border-radius: 8px;
        border: none;
        display: inline-block;
        cursor: pointer;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    /* เมื่อเมาส์ไปชี้ที่ปุ่ม */
    .upload-btn:hover {
        transform: scale(1.1); /* ขยายขึ้นเล็กน้อย */
        opacity: 0.9;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2); /* เพิ่มเงา */
    }

    /* เอฟเฟกต์ตอนกดปุ่ม */
    .upload-btn:active {
        transform: scale(0.95); /* หดลงเล็กน้อย */
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.15); /* ลดเงา */
    }

    /* ปรับสีปุ่มเมื่อ Hover */
    .upload-btn:hover {
        background-color: #0257cc;
    }

    /* ปรับสีปุ่มเมื่อกด */
    .upload-btn:active {
        background-color: #0148a0;
    }

    /* ปรับสไตล์ของ input file ให้เป็น Transparent */
    .upload-btn input[type="file"] {
        opacity: 0;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

</style>
<body class="nav-fixed">
<?php require 'pp-setting-sidenavAccordion.php'; ?>
<div id="layoutSidenav">
    <?php require 'pp-layoutSidenav_nav.php'; ?>
    <div id="layoutSidenav_content">
        <main>
<!--            <header class="page-header page-header-compact page-header-light border-bottom bg-red  mb-4">-->
<!--                <div class="container-xl px-4">-->
<!--                    <div class="page-header-content">-->
<!--                        <div class="row align-items-center justify-content-between pt-3">-->
<!--                            <div class="col-auto mb-3">-->
<!--                                <h1 class="page-header-title text-white">-->
<!--                                    <div class="page-header-icon text-white"><i data-feather="user"></i></div>-->
<!--                                    Account Settings - Profile-->
<!--                                </h1>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </header>-->
            <!-- Main page content-->
            <div class="container-xl px-4 mt-4">
                <!-- Account page navigation-->
<!--                <nav class="nav nav-borders">-->
<!--                    <a class="nav-link active ms-0" href="/projects/mjrqr/pp-account.php">Profile</a>-->
<!--                    <a class="nav-link" href="/projects/mjrqr/master/account-billing.html">Billing</a>-->
<!--                    <a class="nav-link" href="/projects/mjrqr/master/account-security.html">Security</a>-->
<!--                    <a class="nav-link" href="/projects/mjrqr/master/account-notifications.html">Notifications</a>-->
<!--                </nav>-->
<!--                <hr class="mt-0 mb-4" />-->
                <div class="container-xl px-4 mt-4">
                    <div class="card mb-4 mb-xl-0">
                        <div class="card-header bg-red fs-2 fw-bold text-white">Profile Picture</div>
                        <div class="card-body text-center">
                            <div class="container">

                                <!-- ส่วนข้อมูลพนักงาน -->
                                <div class="profile-info">
                                    <h2>👤 ข้อมูลพนักงาน</h2>

                                    <div class="form-group">
                                        <label>ID Staff</label>
                                        <input type="text" value="<?= htmlspecialchars($staff['id_staff']) ?>" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label>ID RFID</label>
                                        <input type="text" value="<?= htmlspecialchars($staff['id_rfid']) ?>" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label>Prefix</label>
                                        <input type="text" value="<?= htmlspecialchars($staff['prefix']) ?>" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" value="<?= htmlspecialchars($staff['name_first']) ?>" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" value="<?= htmlspecialchars($staff['name_last']) ?>" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label>Role Group</label>
                                        <input type="text" value="<?= htmlspecialchars($role_group_name) ?>" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label>Role</label>
                                        <input type="text" value="<?= htmlspecialchars($role) ?>" readonly>
                                    </div>
                                </div>
                                <form id="uploadImageForm" method="post" enctype="multipart/form-data">
                                    <!-- ส่วนรูปโปรไฟล์ -->
                                    <div class="profile-image-container">
                                        <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                                        <img id="preview" class="profile-image" src="<?= $profileImagePath; ?>" alt="Profile Image" />
<!--                                        <label for="imageFile" class="upload-btn">-->
<!--                                            Upload new image-->
<!--                                            <input type="file" id="imageFile" name="imageFile" style="display: none;" accept="image/png, image/jpeg" onchange="startCrop()">-->
<!--                                        </label>-->
                                        <label for="imageFile" class="upload-btn">
                                            Upload new image
                                            <input type="file" id="imageFile" name="imageFile" accept="image/png, image/jpeg" onchange="startCrop()">
                                        </label>



                                    </div>
                                    <!-- พื้นที่แสดงการครอบภาพ -->
                                    <div id="cropContainer" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.8); z-index: 1000; justify-content: center; align-items: center;">
                                        <div style="max-width: 90%; max-height: 90%; display: flex; justify-content: center; align-items: center;">
                                            <img id="imageToCrop" style="max-width: 100%; max-height: 100%;" />
                                        </div>
                                        <div class="button-container" style="position: fixed; bottom: 20px; width: 100%; display: flex; justify-content: center;">
                                            <button type="button" class="btn-custom" id="cropButton" style="margin: 10px;">Crop Image</button>
                                            <button type="button" class="btn-custom" id="cancelCropButton" style="margin: 10px;" onclick="cancelCrop()">Cancel</button>
                                        </div>
                                    </div>

                                    <!-- ปุ่มสำหรับอัปโหลด -->
                                    <br>
                                    <div class="button-container">
                                        <input type="submit" value="Upload Image" class="btn-custom" id="uploadButton" style="display: none;">
                                    </div>
                                </form>
                            </div>
                    </div>
                </div>
        </main>
<!--        <footer class="footer-admin mt-auto footer-light">-->
<!--            <div class="container-xl px-4">-->
<!--                <div class="row">-->
<!--                    <div class="col-md-6 small">Copyright &copy; Your Website 2021</div>-->
<!--                    <div class="col-md-6 text-md-end small">-->
<!--                        <a href="#!">Privacy Policy</a>-->
<!--                        &middot;-->
<!--                        <a href="#!">Terms &amp; Conditions</a>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </footer>-->
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="/projects/mjrqr/js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" crossorigin="anonymous"></script>
<script src="/projects/mjrqr/assets/demo/chart-area-demo.js"></script>
<script src="/projects/mjrqr/assets/demo/chart-pie-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
<script src="/projects/mjrqr/js/datatables/datatables-simple-demo.js"></script>
<script type="text/javascript" src="js/majorette/pp-session.js"></script>
<script type="text/javascript" src="js/majorette/pp-accoount-profile-script.js"></script>




</body>
</html>
