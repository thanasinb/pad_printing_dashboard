<?php
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Job overview by Machine</title>
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
<!--        <script src="js/majorette/pp-dragtable.js"></script>-->
<!--        <script type="text/javascript" src="js/datetimepicker4/moment.min.js"></script>-->
<!--        <script type="text/javascript" src="js/datetimepicker4/tempusdominus-bootstrap-4.min.js"></script>-->
<!--        <link rel="stylesheet" href="css/datetimepicker4/tempusdominus-bootstrap-4.min.css" />-->
<!--        <script type="text/javascript" src="js/majorette/pp-machine-assign-date.js"></script>-->
<!--        <script type="text/javascript" src="js/majorette/pp-machine-currentTaskModal.js"></script>-->
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
                    <div class="container-xl px-4 mt-n10">
                        <!-- Example DataTable for Dashboard Demo-->
                        <div class="card mb-4" id="table-machine">
                            <div class="card-header bg-dark fw-bold text-white">Upload Staff File</div>
                            <div class="card-body">
                                <form class="text-black" action="pp-staff-upload-action.php" method="post" enctype="multipart/form-data">
                                    Select file to upload:
                                    <input class="btn text-black" type="file" name="fileToUpload" id="fileToUpload">
                                    <br>
                                    <input class="btn btn-primary" type="submit" value="Upload!" name="submit">
                                    <br><br>
                                    <?php

                                    ini_set('display_errors', 0);
                                    error_reporting(E_ERROR | E_WARNING | E_PARSE);

                                    if ($_GET['error_code']!=null){
                                        if ($_GET['error_code'])
                                            echo "Upload error! Code: " . $_GET['error_code'];
                                        else
                                            echo "Upload successfully! Code: " . $_GET['error_code'];
                                    }
                                    ?>
                                </form>
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
