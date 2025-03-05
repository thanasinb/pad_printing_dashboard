<?php
session_start();
require 'update/establish.php';
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $password = $_POST['password'];

    // ตรวจสอบและทำลาย Session เดิมหากมีการ Login ซ้อน
    if (isset($_SESSION['username']) && $_SESSION['username'] === $username) {
        $stmt = $conn->prepare("INSERT INTO history (username, action, date_time) VALUES (?, 'Logout (automatic, new login)', NOW())");
        $stmt->bind_param("s", $_SESSION['username']);
        $stmt->execute();
        $stmt->close();

        session_unset();
        session_destroy();
        if (isset($_COOKIE['session_token'])) {
            setcookie("session_token", "", time() - 3600, "/");
        }
    }

    // ดึงข้อมูลผู้ใช้จากฐานข้อมูล
    $sql = "SELECT login.*, role.*, role_group.role_group_name 
            FROM login 
            INNER JOIN staff ON login.id_staff = staff.id_staff 
            INNER JOIN role ON staff.id_role = role.id_role 
            INNER JOIN role_group ON role.role_group = role_group.id_role_group 
            WHERE login.username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $username;
            $_SESSION['role_group'] = $row['role_group'];
            $_SESSION['role_group_name'] = $row['role_group_name'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['last_activity'] = time();

            $session_token = bin2hex(random_bytes(32));
            $_SESSION['session_token'] = $session_token;
            setcookie("session_token", $session_token, time() + (30 * 24 * 60 * 60), "/", "", true, true);

            $stmt = $conn->prepare("INSERT INTO history (username, action, date_time) VALUES (?, 'Login', NOW())");
            $stmt->bind_param("s", $username);
            $stmt->execute();

            header("Location: pp-machine-3.php");
            exit();
        } else {
            header("Location: pp-login.php?error=password_incorrect");
            exit();
        }
    } else {
        header("Location: pp-login.php?error=password_incorrect");
        exit();
    }
}
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
    <link rel="icon" type="image/x-icon" href="assets/img/login.png" />
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
                    if (isset($_GET['error']) && $_GET['error'] == 'password_incorrect') {
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
