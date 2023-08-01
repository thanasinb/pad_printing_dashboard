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
        <script src="js/jquery/jquery.min.js"></script>
        <script src="js/jquery/jquery-ui.min.js"></script>
        <script src="js/majorette/pp-setting-dt-add.js"></script>
    </head>
    <body class="nav-fixed">
    <?php require 'pp-staff-sidenavAccordion.php'; ?>
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
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header">Add New Downtime</div>
                                <div class="card-body">
                                    <form method="post" action="pp-setting-dt-add-action.php" enctype="multipart/form-data">
                                        <!-- Form Group (username)-->
                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="box_code">Box code</label>
                                                <input class="form-control" id="box_code" name="box_code" type="text" maxlength="3" required="required">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="downtime_code">Downtime code</label>
                                                <input class="form-control" id="downtime_code" name="downtime_code" type="text" maxlength="8" required="required">
                                            </div>
                                        </div>
                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-12">
                                                <label class="small mb-1" for="description_eng">Description English</label>
                                                <input class="form-control" id="description_eng" name="description_eng" type="text" maxlength="50" required="required">
                                            </div>
                                        </div>
                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-12">
                                                <label class="small mb-1" for="description_tha">Description Thai</label>
                                                <input class="form-control" id="description_tha" name="description_tha" type="text" maxlength="50">
                                            </div>
                                        </div>
                                        <!-- Save changes button-->
                                        <button id="submit_button" class="btn btn-blue" type="submit" disabled>Add</button>
                                        <br><br>
                                        <?php

                                        ini_set('display_errors', 0);
                                        error_reporting(E_ERROR | E_WARNING | E_PARSE);

                                        if ($_GET['error_code']!=null){
                                            if ($_GET['error_code'])
                                                echo "Add downtime error! Code: " . $_GET['error_code'];
                                            else
                                                echo "Add downtime successfully! Code: " . $_GET['error_code'];
                                        }
                                        ?>
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
        <script src="js/simple-datatables@latest" type="text/javascript"></script>
        <script src="js/datatables/datatables-simple-demo.js"></script>
        <script src="js/litepicker/dist/bundle.js"></script>
        <script src="js/litepicker.js"></script>
    </body>
</html>
