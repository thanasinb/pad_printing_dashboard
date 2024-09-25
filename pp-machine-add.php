-<?php
require 'pp-session-start.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Add New Machine</title>
        <link href="css/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="css/litepicker/dist/css/litepicker.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
        <script data-search-pseudo-elements defer src="js/font-awesome/5.15.3/js/all.min.js"></script>
        <script src="js/feather-icons/4.28.0/feather.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <!--        <script src="js/majorette/pp-machine-add.js"></script>-->
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
                    <div class="container-xl d-flex justify-content-center align-items-center px-4 mt-n10">
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header">Add New Machine</div>
                                <div class="card-body">
                                    <form method="post" action="pp-machine.php">
                                        <!-- Form Group (username)-->
                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="id_mc">Machine number</label>
                                                <input class="form-control" id="id_mc" name="id_mc" type="text" >
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="id_mc_type">Machine type</label>
                                                <select class="form-control" id="id_mc_type" name="id_mc_type">
                                                    <option></option>
                                                    <?php
                                                    require "pp-script-list-mc_type.php";
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-12">
                                                <label class="small mb-1" for="mc_des">Machine description</label>
                                                <textarea class="form-control" id="mc_des" name="mc_des" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <!-- Save changes button-->
                                        <button class="btn btn-blue" id="submit_machine_add" type="submit">Add</button>
                                        <a href="pp-machine.php" style="text-decoration: none"><button class="btn btn-red" type="button">Cancel</button></a>
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
    <script type="text/javascript" src="js/majorette/pp-session.js"></script>

    </body>
</html>
