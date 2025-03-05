<?php
require 'pp-session-start.php';
//require  'pp-mc-get-to-chart.php';

?>
<link rel="stylesheet" href="css/pp-sidenav.css">

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Machine Statistic</title>
    <link href="css/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/litepicker/dist/css/litepicker.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/machine-learning.png" />
    <link href="css/styles.css" rel="stylesheet" />
<!--    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-tooltip"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script data-search-pseudo-elements defer src="js/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="js/feather-icons/4.28.0/feather.min.js"></script>
    <link rel="stylesheet" href="css/reorder-columns/dragtable.css">
    <link rel="stylesheet" href="css/reorder-columns/bootstrap-table.min.css">
    <link rel="stylesheet" href="css/majorette.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/jquery/jquery-ui.min.js"></script>


    <style>
        .chart-container {
            /*width: 1200px; !* Adjust the width as needed *!*/
            /*height: 500px; !* Adjust the height as needed *!*/
            margin: auto; /* This centers the chart */
            display: flex;
            justify-content: center; /* Aligns the chart horizontally */
            align-items: center; /* Aligns the chart vertically */
        }
        .modal-body {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-dialog {
            max-width: 25%; /* ตั้งค่าให้ modal dialog มีความกว้างไม่เกิน 80% ของหน้าจอ */
        }
        .nav-tabs .nav-link {
            color: #ffffff; /* Change this to your desired color */
        }

        /* Change the text color of the active tab */
        .nav-tabs .nav-link.active {
            color: #000000; /* Change this to your desired color */
        }

        .form-container {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            margin-bottom: 20px;
            margin-right: 10px;
        }

        /*.form-container {*/
        /*    margin-bottom: 10px;*/
        /*}*/

        label {
            margin-right: 10px;
        }

        input[type="date"] {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 6px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<style>

    /* เอฟเฟกต์ปุ่ม Confirm (สีน้ำเงิน) */
    .btn-confirm-modal {
        transition: all 0.2s ease-in-out;
        background-color: #007bff; /* สีน้ำเงิน */
        color: white !important;
        font-size: 16px;
        font-weight: bold;
        padding: 6px 20px;
        border-radius: 6px;
        border: none;
        display: inline-block;
        cursor: pointer;
        text-align: center;
        margin-left: 10px;
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


</style>
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
            <div class="container-xxl px-4 mt-n10">
                <div class="row">
                    <div class="container-xxl-8">
                        <!-- Tabbed dashboard card example-->
                        <div class="card mb-4">
                            <div class="card-header bg-red fw-bold text-white fs-4 d-flex justify-content-between">
<!--                                <div>Machine Statistic</div>-->
                                <ul class="nav nav-tabs card-header-tabs" id="dashboardNav" role="tablist">
                                    <li class="nav-item me-1">
                                        <a class="nav-link active" id="overview-pill" href="#overview" data-bs-toggle="tab" role="tab" aria-controls="overview" aria-selected="true">  Machine Overview  </a>
                                    </li>
                                    <li class="nav-item me-1">
                                        <a class="nav-link" id="itemoverall-pill" href="#itemoverall" data-bs-toggle="tab" role="tab" aria-controls="itemoverall" aria-selected="false">  Item Overall  </a>
                                    </li>
                                    <li class="nav-item me-1">
                                        <a class="nav-link" id="activities-pill" href="#activities" data-bs-toggle="tab" role="tab" aria-controls="activities" aria-selected="false">  Activities DT  </a>
                                    </li>
                                    <li class="nav-item me-1">
                                        <a class="nav-link" id="mcovertime-pill" href="#mcovertime" data-bs-toggle="tab" role="tab" aria-controls="mcovertime" aria-selected="false">  Machine Overtime  </a>
                                    </li>
                                </ul>
<!--                                <div>-->
<!--                                    <span id="hours"></span> :-->
<!--                                    <span id="minutes"></span> :-->
<!--                                    <span id="seconds"></span>-->
<!--                                </div>-->

                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="dashboardNavContent">
<!--                                    ข้อมูลกราฟก่อนระบุช่วงวัน-->
                                    <?php require  'pp-mc-get-to-chart.php'; ?>
                                    <!-- งานที่ทำทั้งหมด -->
                                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-pill">
                                        <div class="form-container">
                                            <form method="post" id="dateForm">
                                                <label for="start_date">Start Date:</label>
                                                <input type="date" id="start_date" name="start_date" required>
                                                <label for="end_date">End Date:</label>
                                                <input type="date" id="end_date" name="end_date" required>
                                                <button type="submit" class=" upload-button btn-confirm-modal">Update</button>
                                            </form>
                                            <script>
                                                // ตั้งค่า max date ใน input[type="date"] เป็นวันปัจจุบัน
                                                const today = new Date().toISOString().split('T')[0];
                                                document.getElementById('start_date').setAttribute('max', today);
                                                document.getElementById('end_date').setAttribute('max', today);
                                            </script>
                                        </div>
                                        <div class="chart-container">
                                            <canvas id="machineChart"></canvas>
                                        </div>
                                    </div>
<!--                                    //กราฟจำนวนงาน//-->
                                    <div class="tab-pane fade" id="itemoverall" role="tabpanel" aria-labelledby="itemoverall-pill">
                                        <div class="form-container">
                                            <form id="itemoverallDateForm">
                                                <label for="itemoverall_start_date">Start Date:</label>
                                                <input type="date" id="itemoverall_start_date" name="start_date">
                                                <label for="itemoverall_end_date">End Date:</label>
                                                <input type="date" id="itemoverall_end_date" name="end_date">
                                                <button type="submit" class=" upload-button btn-confirm-modal">Update</button>
                                            </form>
                                            <script>
                                                // ตั้งค่า max date ใน input[type="date"] เป็นวันปัจจุบัน
                                                document.addEventListener("DOMContentLoaded", function () {
                                                    const today = new Date().toISOString().split('T')[0];
                                                    document.getElementById('itemoverall_start_date').setAttribute('max', today);
                                                    document.getElementById('itemoverall_end_date').setAttribute('max', today);
                                                });
                                            </script>
                                        </div>
                                        <div class="chart-container">
                                            <canvas id="itemoverallChart"></canvas>
                                        </div>
                                    </div>

<!--                                    //กราฟจำนวนครั้งที่ downtime//-->
                                    <div class="tab-pane fade" id="activities" role="tabpanel" aria-labelledby="activities-pill">
                                        <div class="form-container">
                                            <form id="downtimeDateForm">
                                                <label for="downtime_start_date">Start Date:</label>
                                                <input type="date" id="downtime_start_date" name="start_date">
                                                <label for="downtime_end_date">End Date:</label>
                                                <input type="date" id="downtime_end_date" name="end_date">
                                                <button type="submit" class=" upload-button btn-confirm-modal">Update</button>
                                            </form>
                                        </div>
                                        <script>
                                            // ตั้งค่า max date ใน input[type="date"] เป็นวันปัจจุบัน
                                            document.addEventListener("DOMContentLoaded", function () {
                                                const today = new Date().toISOString().split('T')[0];
                                                document.getElementById('downtime_start_date').setAttribute('max', today);
                                                document.getElementById('downtime_end_date').setAttribute('max', today);
                                            });
                                        </script>
                                        <div class="chart-container">
                                            <canvas id="downtimeChart"></canvas>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="mcovertime" role="tabpanel" aria-labelledby="mcovertime-pill">
                                        <div class="form-container">
                                            <form id="mcovertimeDateForm">
                                                <label for="mcovertime_start_date">Start Date:</label>
                                                <input type="date" id="mcovertime_start_date" name="start_date">
                                                <label for="mcovertime_end_date">End Date:</label>
                                                <input type="date" id="mcovertime_end_date" name="end_date">
                                                <button type="submit" class=" upload-button btn-confirm-modal">Update</button>
                                            </form>
                                            <script>
                                                // ตั้งค่า max date ใน input[type="date"] เป็นวันปัจจุบัน
                                                document.addEventListener("DOMContentLoaded", function () {
                                                    const today = new Date().toISOString().split('T')[0];
                                                    document.getElementById('mcovertime_start_date').setAttribute('max', today);
                                                    document.getElementById('mcovertime_end_date').setAttribute('max', today);
                                                });
                                            </script>
                                        </div>
                                        <div class="chart-container">
                                            <canvas id="mcovertimeChart"></canvas>
                                        </div>
                                    </div>




                                </div>
                            </div>
                            <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-sm"> <!-- ✅ ขนาดปรับตามเนื้อหา -->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="detailModalLabel">Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p id="modalContent"></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>
<p>Total Downtime: <span id="totalDowntime">0</span> hours</p>

<input type="hidden" id="machines" value='<?php echo $machines_json; ?>'>
<input type="hidden" id="totalQuantities" value='<?php echo $totalQuantities_json; ?>'>

<input type="hidden" id="machineDowntime" value='<?php echo $machineDowntime_json; ?>'>
<input type="hidden" id="jobCounts" value='<?php echo $jobCounts_json; ?>'>
<input type="hidden" id="taskDetails" value='<?php echo $taskDetails_json; ?>'>
<input type="hidden" id="downtimeDurations" value='<?php echo json_encode($downtimeDurations); ?>'>
<input type="hidden" id="downtimeDetails" value='<?php echo json_encode($downtimeDetails); ?>'>
<input type="hidden" id="totalDowntime" value='<?php echo json_encode(round($totalDowntime, 2)); ?>'>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!--<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>-->
<script src="js/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
<script src="js/majorette/chart-script.js"></script>
<script src="js/majorette/chart-script-itemoverall.js"></script>
<script src="js/majorette/chart-script-dt.js"></script>
<script src="js/majorette/chart-script-mcovertime.js"></script>
<script src="js/simple-datatables@latest" type="text/javascript"></script>
<script src="js/datatables/datatables-staff.js"></script>
<script src="js/litepicker/dist/bundle.js"></script>
<script src="js/litepicker.js"></script>
<!--<script src="js/majorette/pp-time-stamp.js"></script>-->
<script type="text/javascript" src="js/majorette/pp-session.js"></script>

</body>
</html>
