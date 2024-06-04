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
    <title>Operator Statistic</title>
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
    <script type="text/javascript" src="js/majorette/pp-account-user-add.js"></script>
    <!-- css style -->
    <link href="css/pp-account-user.css" rel="stylesheet" />
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
                        <div>Operator Statistic</div>
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
                            require_once 'pp-op-statistic-table-head.php';
                            ?>
                            </thead>
                            <tbody id="table_body">


                            </tbody>

                        </table>

                </div>
                </div>
                <!-- Modals and other elements -->
                <!-- Modal Content here... -->

                <script src="js/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
                <script src="js/scripts.js"></script>
                <script src="js/simple-datatables@latest" type="text/javascript"></script>
                <script src="js/datatables/datatables-staff.js"></script>
                <script src="js/litepicker/dist/bundle.js"></script>
                <script src="js/litepicker.js"></script>
                <script  src="js/majorette/pp-time-stamp.js"></script>
                <script type="text/javascript" src="js/majorette/pp-session.js"></script>

            </div>
        </main>
    </div>
</div>
</body>
</html>
