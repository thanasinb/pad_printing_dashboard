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
        <title>Add New Job</title>
        <link href="css/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="css/litepicker/dist/css/litepicker.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
        <script data-search-pseudo-elements defer src="js/font-awesome/5.15.3/js/all.min.js"></script>
        <script src="js/feather-icons/4.28.0/feather.min.js"></script>
    </head>
    <body class="nav-fixed">
    <?php require 'pp-machine-sidenavAccordion.php'; ?>
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
                    <div class="container-xl d-flex justify-content-center align-items-center px-4 mt-n10">
                        <!-- Example DataTable for Dashboard Demo-->
                        <div class="col-xl-9">
                            <div class="card mb-4">
                                <div class="card-header">Add New Job</div>
                                <div class="card-body">
                                    <form method="post" action="pp-job-dashboard.php">
                                        <!-- Form Group (username)-->
                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-4">
                                                <label class="small mb-1" for="id_job">Job ID</label>
                                                <input class="form-control" id="id_job" name="id_job" type="number" placeholder="Enter Job ID (Number)" />
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small mb-1" for="work_order">Work Order</label>
                                                <input class="form-control" id="work_order" name="work_order" type="text" />
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small mb-1" for="item_no">Item No.</label>
                                                <input class="form-control" id="item_no" name="item_no" type="text" />
                                            </div>
                                        </div>
                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-8">
                                                <label class="small mb-1" for="item_des">Item Description</label>
                                                <input class="form-control" id="item_des" name="item_des" type="text" />
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small mb-1" for="sales_job">Sales/Job</label>
                                                <input class="form-control" id="sales_job" name="sales_job" type="text" />
                                            </div>
                                        </div>
                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-4">
                                                <label class="small mb-1" for="qty_order">Qty Ordered</label>
                                                <input class="form-control" id="qty_order" name="qty_order" type="number" />
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small mb-1" for="prod_line">Production Line</label>
                                                <input class="form-control" id="prod_line" name="prod_line" type="text" />
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small mb-1" for="prod_rate">Production Rate</label>
                                                <input class="form-control" id="prod_rate" name="prod_rate" type="number" />
                                            </div>
                                            <!--                                        <div class="col-md-3">-->
                                            <!--                                            <label class="small mb-1" for="qty_completed">Qty Completed</label>-->
                                            <!--                                            <input class="form-control" id="qty_completed" name="qty_completed" type="number" />-->
                                            <!--                                        </div>-->
                                            <!--                                        <div class="col-md-3">-->
                                            <!--                                            <label class="small mb-1" for="qty_rejected">Qty Rejected</label>-->
                                            <!--                                            <input class="form-control" id="qty_rejected" name="qty_rejected" type="number" />-->
                                            <!--                                        </div>-->
                                        </div>
                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-4">
                                                <label class="small mb-1" for="date_order_job">Order Date</label>
                                                <input class="form-control" id="date_order_job" name="date_order_job" type="date" />
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small mb-1" for="date_release_job">Release Date</label>
                                                <input class="form-control" id="date_release_job" name="date_release_job" type="date" />
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small mb-1" for="date_due_job">Due Date</label>
                                                <input class="form-control" id="date_due_job" name="date_due_job" type="date" />
                                            </div>
                                        </div>
                                        <!--                                    <div class="row gx-3 mb-3">-->
                                        <!--                                    </div>-->
                                        <!-- Save changes button-->
                                        <button class="btn btn-blue" type="submit">Add</button>
                                        <a href="pp-job-dashboard.php" style="text-decoration: none">
                                            <button class="btn btn-red" type="button">Cancel</button>
                                        </a>
                                    </form>
                                </div>
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
