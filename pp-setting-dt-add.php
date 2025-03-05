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
        <title>Add New Downtime</title>
        <link href="css/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="css/litepicker/dist/css/litepicker.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="assets/img/settings.png" />
        <script data-search-pseudo-elements defer src="js/font-awesome/5.15.3/js/all.min.js"></script>
        <script src="js/feather-icons/4.28.0/feather.min.js"></script>
        <script src="js/jquery/jquery.min.js"></script>
        <script src="js/jquery/jquery-ui.min.js"></script>
        <script src="js/majorette/pp-setting-dt-add.js"></script>
    </head>
    <style>
        /* เอฟเฟกต์ปุ่ม Confirm (สีน้ำเงิน) */
        .btn-add-downtime {
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
        .btn-add-downtime:hover {
            transform: scale(1.1);
            opacity: 0.9;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            background-color: #0069d9; /* สีน้ำเงินเข้มขึ้น */
        }

        /* เอฟเฟกต์ตอนกดปุ่ม Confirm */
        .btn-add-downtime:active {
            transform: scale(0.95);
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.15);
            background-color: #0056b3;
        }


        /* ปุ่มใน Dark Mode */
        body.dark-mode .btn-add-downtime {
            background-color: #375a7f !important; /* สีฟ้าหม่น */
            color: #ffffff !important; /* สีข้อความขาว */
            border: 1px solid #444444 !important; /* เส้นขอบเข้ม */
        }
        /* Hover ใน Dark Mode */
        body.dark-mode .btn-add-downtime:hover {
            background-color: #3b566a !important; /* สีฟ้าหม่นเข้มขึ้นเมื่อ hover */
        }

    </style>
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
                    <div class="container-xl d-flex justify-content-center align-items-center px-2 mt-n15" style="height: 100vh;">
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header bg-red text-white">Add New Downtime</div>
                                <div class="card-body">
                                    <form id="add_downtime_form" method="post" action="pp-setting-dt-add-action.php">
                                        <div class="mb-3">
                                            <label for="box_code" class="form-label">Box Code</label>
                                            <input type="text" class="form-control" id="box_code" name="box_code" maxlength="3" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="downtime_code" class="form-label">Downtime Code</label>
                                            <input type="text" class="form-control" id="downtime_code" name="downtime_code" maxlength="8" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="description_eng" class="form-label">Description (English)</label>
                                            <input type="text" class="form-control" id="description_eng" name="description_eng" maxlength="50" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="description_tha" class="form-label">Description (Thai)</label>
                                            <input type="text" class="form-control" id="description_tha" name="description_tha" maxlength="50">
                                        </div>
                                        <button type="submit" class="btn btn-add-downtime" id="submit_button" disabled>Add Downtime</button>
                                    </form>
                                    <br>
                                    <?php
                                    if (isset($_GET['error_code'])) {
                                        if ($_GET['error_code'] == "success") {
                                            echo '<div class="alert alert-success mt-3">Add downtime successfully!</div>';
                                        } else {
                                            echo '<div class="alert alert-danger mt-3">Add downtime error! Code: ' . htmlspecialchars($_GET['error_code']) . '</div>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            let inputs = document.querySelectorAll("#box_code, #downtime_code, #description_eng");
                            let submitButton = document.getElementById("submit_button");

                            function checkInputs() {
                                let allFilled = Array.from(inputs).every(input => input.value.trim() !== "");
                                submitButton.disabled = !allFilled;
                            }

                            inputs.forEach(input => {
                                input.addEventListener("input", checkInputs);
                            });
                        });
                    </script>
                </main>
            </div>
        </div>



    <script src="js/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/scripts.js"></script>
        <script src="js/simple-datatables@latest" type="text/javascript"></script>
        <script src="js/datatables/datatables-simple-demo.js"></script>
        <script src="js/litepicker/dist/bundle.js"></script>
        <script src="js/litepicker.js"></script>
    <script type="text/javascript" src="js/majorette/pp-session.js"></script>

    </body>
</html>
