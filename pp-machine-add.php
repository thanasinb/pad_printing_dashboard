<?php
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
        <link rel="icon" type="image/x-icon" href="assets/img/machine-learning.png" />
        <script data-search-pseudo-elements defer src="js/font-awesome/5.15.3/js/all.min.js"></script>
        <script src="js/feather-icons/4.28.0/feather.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="css/reorder-columns/dragtable.css">
        <link rel="stylesheet" href="css/reorder-columns/bootstrap-table.min.css">
        <link rel="stylesheet" href="css/majorette.css">
        <script src="js/jquery/jquery.min.js"></script>
        <script src="js/jquery/jquery-ui.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-tooltip"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>

        <!--        <script src="js/majorette/pp-machine-add.js"></script>-->
    </head>
    <style>

        /* เอฟเฟกต์ปุ่ม Confirm (สีน้ำเงิน) */
        .btn-confirm-modal {
            transition: all 0.2s ease-in-out;
            background-color: #007bff; /* สีน้ำเงิน */
            color: white !important;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            display: inline-block;
            cursor: pointer;
            text-align: center;

        }

        /* เมื่อเมาส์ไปชี้ที่ปุ่ม Confirm */
        .btn-confirm-modal:hover {
            transform: scale(1.1);
            opacity: 0.9;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            background-color: #0069d9; /* สีน้ำเงินเข้มขึ้น */
        }

        /* เอฟเฟกต์ตอนกดปุ่ม Confirm */
        .btn-confirm-modal:active {
            transform: scale(0.95);
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.15);
            background-color: #0056b3;
        }


        /* ปุ่มใน Dark Mode */
        body.dark-mode .btn-confirm-modal {
            background-color: #375a7f !important; /* สีฟ้าหม่น */
            color: #ffffff !important; /* สีข้อความขาว */
            border: 1px solid #444444 !important; /* เส้นขอบเข้ม */
        }

        /* Hover ใน Dark Mode */
        body.dark-mode .btn-confirm-modal:hover {
            background-color: #3b566a !important; /* สีฟ้าหม่นเข้มขึ้นเมื่อ hover */
        }

        /* เอฟเฟกต์ปุ่ม Close (สีม่วง) */
        .btn-close-modal {
            transition: all 0.2s ease-in-out;
            background-color:  #f44336; /* สีม่วง */
            color: white !important;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            display: inline-block;
            cursor: pointer;
            text-align: center;
            margin-left: 5px;

        }

        /* เมื่อเมาส์ไปชี้ที่ปุ่ม Close */
        .btn-close-modal:hover {
            transform: scale(1.1);
            opacity: 0.9;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            background-color: #d32f2f;
        }

        /* เอฟเฟกต์ตอนกดปุ่ม Close */
        .btn-close-modal:active {
            transform: scale(0.95);
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.15);
            background-color: #a52020;
        }
        /* ปุ่มใน Dark Mode */
        body.dark-mode .btn-close-modal {
            background-color:#A52A2A !important; /* สีม่วงหม่น */
            border-color: #A52A2A !important; /* สีเส้นขอบ */
            color: white !important; /* สีตัวอักษร */
        }

        /* Hover ใน Dark Mode */
        body.dark-mode .btn-close-modal:hover {
            background-color: #8c2424 !important; /* สีม่วงหม่น */
        }
    </style>
    <body class="nav-fixed">
    <?php require 'pp-setting-sidenavAccordion.php'; ?>
    <div id="layoutSidenav">
        <?php require 'pp-layoutSidenav_nav.php'; ?>
        <div id="layoutSidenav_content">
                <main>
                    <header class="page-header page-header-dark pb-4">
                        <div class="container-xl px-4">
                            <div class="page-header-content pt-4">
                            </div>
                        </div>
                    </header>
                    <!-- Main page content-->
                    <div class="container-xl d-flex justify-content-center align-items-center px-2 mt-n5" >
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header bg-red text-white">Add New Machine</div>
                                <div class="card-body">
                                    <form method="post" action="pp-machine-3.php">
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
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="id_cam">Camera number</label>
                                                <input class="form-control" id="id_cam" name="id_cam" type="text" >
                                            </div>
                                        </div>
                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-12">
                                                <label class="small mb-1" for="mc_des">Machine description</label>
                                                <textarea class="form-control" id="mc_des" name="mc_des" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <!-- Save changes button-->
                                        <button class="btn btn-confirm-modal" id="submit_machine_add" type="submit">Add</button>
                                        <a href="pp-machine-add.php" style="text-decoration: none"><button class="btn btn-close-modal" type="button">Cancel</button></a>
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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/majorette/chart-script.js"></script>
    <script src="js/majorette/chart-script-dt.js"></script>
    <script src="js/simple-datatables@latest" type="text/javascript"></script>
    <script src="js/datatables/datatables-staff.js"></script>
    <script type="text/javascript" src="js/majorette/pp-session.js"></script>


    </body>
</html>
