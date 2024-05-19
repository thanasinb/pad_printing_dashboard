<?php
session_start();

// เชื่อมต่อกับไฟล์เชื่อมต่อฐานข้อมูล
require 'update/establish.php';

// ตรวจสอบว่ามีการส่งข้อมูล username และ password มาหรือไม่
if (isset($_POST['username']) && isset($_POST['password'])) {
    // รับค่า username และ password จากฟอร์ม
    $username = $_POST['username'];
    $password = $_POST['password'];

    // คำสั่ง SQL สำหรับเลือกข้อมูลผู้ใช้จากฐานข้อมูล
    $sql = "SELECT login.*, role.*
            FROM login
            INNER JOIN staff ON login.id_staff = staff.id_staff
            INNER JOIN role ON staff.id_role = role.id_role
            WHERE login.username='$username' AND login.password='$password' AND (role.role_group = 2 OR role.role_group = 3)";

    // ทำการคิวรีฐานข้อมูล
    $result = $conn->query($sql);

    // ตรวจสอบว่ามีข้อมูลผู้ใช้ในฐานข้อมูลหรือไม่
    if ($result->num_rows > 0) {
        // พบข้อมูลผู้ใช้ที่ตรงกับ username และ password
        // เก็บข้อมูล username ใน Session
        $_SESSION['username'] = $username;

        // เพิ่มรายการประวัติการเข้าสู่ระบบลงในฐานข้อมูล history
        $login_user = $_SESSION['username'];
        $history_sql = "INSERT INTO history (username, action, date_time)
                        VALUES ('$login_user', 'Login', NOW())";
        $conn->query($history_sql);

        // ส่งผู้ใช้ไปยังหน้า pp-machine-3.php
        header("Location: pp-machine-3.php");
        exit();
    } else {
        // ไม่พบข้อมูลผู้ใช้หรือ username/password ไม่ตรง
        // ส่งผู้ใช้กลับไปยังหน้า pp-login.php พร้อมส่งข้อความผิดพลาด
        header("Location: pp-login.php?error=password_incorrect");
        exit();
    }
}

// ปิดการเชื่อมต่อกับฐานข้อมูล
require 'update/terminate.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Login Homepage</title>
    <link href="css/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/litepicker/dist/css/litepicker.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
    <script data-search-pseudo-elements defer src="js/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="js/feather-icons/4.28.0/feather.min.js"></script>
    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/jquery/jquery-ui.min.js"></script>
    <script src="js/majorette/pp-setting-dt-add.js"></script>


</head>
<body class="nav-fixed">
<?php require 'pp-login-sidenavAccordion.php'; ?>

<div class="container mt-15">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">Login</div>
                <div class="card-body">
                    <?php
                    // ตรวจสอบว่ามีข้อความผิดพลาดที่ส่งมาจาก pp-login.php หรือไม่
                    if(isset($_GET['error']) && $_GET['error'] == 'password_incorrect') {
                        echo '<div class="alert alert-danger" role="alert">รหัสผ่านผิด!</div>';
                    }
                    ?>
                    <form id="loginForm" action="" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
<script src="js/simple-datatables@latest" type="text/javascript"></script>
<script src="js/datatables/datatables-simple-demo.js"></script>
<script src="js/litepicker/dist/bundle.js"></script>
<script src="js/litepicker.js"></script>
</body>
</html>
