<?php
//require 'pp-job-add-script.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - SB Admin Pro</title>
    <link href="css/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/litepicker/dist/css/litepicker.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
    <script data-search-pseudo-elements defer src="js/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="js/feather-icons/4.28.0/feather.min.js"></script>
<!--    <link rel="stylesheet" href="css/reorder-columns/dragtable.css">-->
<!--    <link rel="stylesheet" href="css/reorder-columns/bootstrap-table.min.css">-->
    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/jquery/jquery-ui.min.js"></script>
<!--    <script src="js/reorder-columns/jquery.dragtable.js"></script>-->
<!--    <script src="js/reorder-columns/bootstrap-table.min.js"></script>-->
<!--    <script src="js/reorder-columns/bootstrap-table-reorder-columns.js"></script>-->
    <script src="js/majorette/pp-dragtable.js"></script>
    <script src="js/majorette/pp-machine-list-task.js"></script>
</head>
<body class="nav-fixed">
<?php
require 'pp-planning-sidenavAccordion.php';
?>
<div id="layoutSidenav">
    <?php
    require 'pp-planning-layoutSidenav_nav.php';
    ?>
    <div id="layoutSidenav_content">
        <main>
            <header class="page-header page-header-dark pb-5">
                <div class="container-xl px-4">
                    <div class="page-header-content pt-4">
                    </div>
                </div>
            </header>
            <!-- Main page content-->
            <div class="container-xl px-4 mt-n10">
                <!-- Example DataTable for Dashboard Demo-->
                <div class="card mb-4">
                    <div class="card-header bg-dark fw-bold text-white">Manufacturing orders to be assigned to Machine: <?php echo $_POST["id_mc"]; ?></div>
                    <div class="card-body">
                        <table id="datatablesSimple" class="table table-striped table-sm">
                            <thead class="table-dark">
                            <?php require 'pp-machine-list-task-table-head.php' ?>
                            </thead>
                            <tfoot>
                            <?php require 'pp-machine-list-task-table-head.php' ?>
                            </tfoot>
                            <tbody>
                            <?php require 'pp-machine-list-task-script.php'; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<script src="js/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
<script src="js/Chart.js/2.9.4/Chart.min.js"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="js/simple-datatables@latest" type="text/javascript"></script>
<script src="js/datatables/datatables-machine-list-task.js"></script>
<script src="js/litepicker/dist/bundle.js"></script>
<script src="js/litepicker.js"></script>
</body>
</html>
