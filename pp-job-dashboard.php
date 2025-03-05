<?php
require 'pp-session-start.php';

require 'pp-job-add-script.php';
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
                    <!-- Main page content-->
                    <div class="container-xl px-4 mt-n10">
                        <!-- Example DataTable for Dashboard Demo-->
                        <div class="card mb-4">
                            <div class="card-header">Jobs</div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Job ID</th>
                                            <th>Work Order</th>
                                            <th>Item Number</th>
                                            <th>Item Description</th>
                                            <th>Sales<br>/Job</th>
                                            <th>Qty Ord.</th>
                                            <th>Qty Comp.</th>
                                            <th>Qty Rej.</th>
                                            <th>Order Date</th>
                                            <th>Release Date</th>
                                            <th>Due Date</th>
                                            <th>Prod. Line</th>
                                            <th>Prod. Rate</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Job ID</th>
                                            <th>Work Order</th>
                                            <th>Item Number</th>
                                            <th>Item Description</th>
                                            <th>Sales<br>/Job</th>
                                            <th>Qty Ord.</th>
                                            <th>Qty Comp.</th>
                                            <th>Qty Rej.</th>
                                            <th>Order Date</th>
                                            <th>Release Date</th>
                                            <th>Due Date</th>
                                            <th>Prod. Line</th>
                                            <th>Prod. Rate</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    <?php
                                    require 'pp-job-list-script.php';
                                    ?>
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
        <script src="js/datatables/datatables-simple-demo.js"></script>
        <script src="js/litepicker/dist/bundle.js"></script>
        <script src="js/litepicker.js"></script>
    </body>
</html>
