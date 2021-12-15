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
        <link rel="stylesheet" href="css/reorder-columns/dragtable.css">
        <link rel="stylesheet" href="css/reorder-columns/bootstrap-table.min.css">
        <script src="js/jquery/jquery.min.js"></script>
        <script src="js/jquery/jquery-ui.min.js"></script>
        <script src="js/reorder-columns/jquery.dragtable.js"></script>
        <script src="js/reorder-columns/bootstrap-table.min.js"></script>
        <script src="js/reorder-columns/bootstrap-table-reorder-columns.js"></script>
        <script src="js/majorette/pp-dragtable.js"></script>
        <script src="js/majorette/pp-planning-assign-machine.js"></script>
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
<!--                            <div class="card-header border-bottom">-->
<!--                                <ul class="nav nav-tabs card-header-tabs" id="dashboardNav" role="tablist">-->
<!--                                    <li class="nav-item me-1"><a class="nav-link active" id="overview-pill" href="#overview" data-bs-toggle="tab" role="tab" aria-controls="overview" aria-selected="true">Queues</a></li>-->
<!--                                    <li class="nav-item"><a class="nav-link" id="activities-pill" href="#activities" data-bs-toggle="tab" role="tab" aria-controls="activities" aria-selected="false">Jobs</a></li>-->
<!--                                </ul>-->
<!--                            </div>-->
                            <div class="card-body">
                                <table id="datatablesSimple" class="table table-striped table-sm">
                                    <thead class="table-dark">
                                        <tr class="fw-bold">
                                            <th>Assigned MC</th>
                                            <th>Job ID</th>
                                            <th>Work Ord.</th>
<!--                                            <th>Sales/Job</th>-->
<!--                                            <th>Prod. Line</th>-->
                                            <th>Item No.</th>
<!--                                            <th>Item Desc.</th>-->
<!--                                            <th>Mold</th>-->
<!--                                            <th>Site</th>-->
<!--                                            <th>Type</th>-->
<!--                                            <th>Work Ctr</th>-->
                                            <th>Machine</th>
                                            <th>Operation</th>
<!--                                            <th>Op. Desc.</th>-->
<!--                                            <th>Qty Ord.</th>-->
                                            <th>Qty Comp.</th>
                                            <th>Qty Open</th>
                                            <th>Due Date</th>
<!--                                            <th>WO Status</th>-->
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr class="fw-bold">
                                            <th>Assigned MC</th>
                                            <th>Job ID</th>
                                            <th>Work Ord.</th>
                                            <!--                                            <th>Sales/Job</th>-->
                                            <!--                                            <th>Prod. Line</th>-->
                                            <th>Item No.</th>
                                            <!--                                            <th>Item Desc.</th>-->
                                            <!--                                            <th>Mold</th>-->
                                            <!--                                            <th>Site</th>-->
                                            <!--                                            <th>Type</th>-->
                                            <!--                                            <th>Work Ctr</th>-->
                                            <th>Machine</th>
                                            <th>Operation</th>
                                            <!--                                            <th>Op. Desc.</th>-->
<!--                                            <th>Qty Ord.</th>-->
                                            <th>Qty Comp.</th>
                                            <th>Qty Open</th>
                                            <th>Due Date</th>
                                            <!--                                            <th>WO Status</th>-->
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    <?php
                                    require 'pp-planning-script-2.php';
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
