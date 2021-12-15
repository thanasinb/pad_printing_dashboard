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
        <script src="js/majorette/pp-staff-add-validate-input.js"></script>
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
                                <div class="card-header">Add New Staff</div>
                                <div class="card-body">
                                    <form method="post" action="pp-staff-add-action.php" enctype="multipart/form-data">
                                        <!-- Form Group (username)-->
                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="id_staff">Staff ID</label>
                                                <input class="form-control" id="id_staff" name="id_staff" type="text" maxlength="6" required="required">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="id_rfid">RFID</label>
                                                <input class="form-control" id="id_rfid" name="id_rfid" type="text" maxlength="10" required="required">
                                            </div>
                                        </div>
                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="prefix">Prefix</label>
                                                <select class="form-control" id="prefix" name="prefix" required="required">
                                                    <option value="0"></option>
                                                    <option value="1">นาย</option>
                                                    <option value="2">นาง</option>
                                                    <option value="3">นางสาว</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="name_first">First name</label>
                                                <input class="form-control" id="name_first" name="name_first" type="text" required="required">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="name_last">Last name</label>
                                                <input class="form-control" id="name_last" name="name_last" type="text" required="required">
                                            </div>
                                        </div>
                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="id_role">Role</label>
                                                <select class="form-control" id="id_role" name="id_role" required="required">
                                                    <option value="0"></option>
                                                    <option value="1">Operator</option>
                                                    <option value="2">Technician</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="id_shif">Team</label>
<!--                                                <input class="form-control" id="id_shif" name="id_shif" type="text" required="required">-->
                                                <select class="form-control" id="id_shif" name="id_shif" required="required">
                                                    <option value="0"></option>
                                                    <option value="A">A</option>
                                                    <option value="B">B</option>
                                                    <option value="C">C</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-12">
                                                Select picture to upload:
                                                <input class="btn text-black" type="file" name="fileToUpload" id="fileToUpload">
                                                <br>
<!--                                                <input class="btn btn-primary" type="submit" value="Upload!" name="submit">-->
<!--                                                <br><br>-->
                                                <?php
//                                                if ($_GET['error_code']!=null){
//                                                    if ($_GET['error_code'])
//                                                        echo "Upload error! Code: " . $_GET['error_code'];
//                                                    else
//                                                        echo "Upload successfully! Code: " . $_GET['error_code'];
//                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <!-- Save changes button-->
                                        <button id="submit_button" class="btn btn-blue" type="submit" disabled>Add</button>
<!--                                        <a href="pp-staff.php" style="text-decoration: none"><button class="btn btn-red" type="button">Cancel</button></a>-->
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
