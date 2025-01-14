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
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-tooltip"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script data-search-pseudo-elements defer src="js/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="js/feather-icons/4.28.0/feather.min.js"></script>
    <link rel="stylesheet" href="css/reorder-columns/dragtable.css">
    <link rel="stylesheet" href="css/reorder-columns/bootstrap-table.min.css">
    <link rel="stylesheet" href="css/majorette.css">
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
                                        <a class="nav-link active" id="overview-pill" href="#overview" data-bs-toggle="tab" role="tab" aria-controls="overview" aria-selected="true">Machine Chart Overview</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="activities-pill" href="#activities" data-bs-toggle="tab" role="tab" aria-controls="activities" aria-selected="false">Activities DT</a>
                                    </li>
                                </ul>
                                <div>
                                    <span id="hours"></span> :
                                    <span id="minutes"></span> :
                                    <span id="seconds"></span>
                                </div>

                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="dashboardNavContent">
                                    <!-- Dashboard Tab Pane 1-->
                                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-pill">
                                        <div class="form-container">
                                            <form method="post" id="dateForm">
                                                <label for="start_date">Start Date:</label>
                                                <input type="date" id="start_date" name="start_date" required>
                                                <label for="end_date">End Date:</label>
                                                <input type="date" id="end_date" name="end_date" required>
                                                <button type="submit">Update</button>
                                            </form>
                                        </div>
                                        <div class="chart-container">
                                            <canvas id="machineChart"></canvas>
                                            <?php require  'pp-mc-get-to-chart.php'; ?>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="activities" role="tabpanel" aria-labelledby="activities-pill">
                                        <div class="form-container">
                                            <form id="downtimeDateForm">
                                                <label for="downtime_start_date">Start Date:</label>
                                                <input type="date" id="downtime_start_date" name="start_date">
                                                <label for="downtime_end_date">End Date:</label>
                                                <input type="date" id="downtime_end_date" name="end_date">
                                                <button type="submit">Update</button>
                                            </form>
                                        </div>
                                        <div class="chart-container">
                                            <canvas id="downtimeChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title " id="detailModalLabel">Details</h5>
                                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                            <!--                                                <span aria-hidden="true">&times;</span>-->
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
<input type="hidden" id="machines" value='<?php echo $machines_json; ?>'>
<input type="hidden" id="machineDowntime" value='<?php echo $machineDowntime_json; ?>'>
<input type="hidden" id="jobCounts" value='<?php echo $jobCounts_json; ?>'>
<input type="hidden" id="taskDetails" value='<?php echo $taskDetails_json; ?>'>
<input type="hidden" id="downtimeDurations" value='<?php echo json_encode($downtimeDurations); ?>'>
<input type="hidden" id="downtimeDetails" value='<?php echo json_encode($downtimeDetails); ?>'>
<input type="hidden" id="totalDowntime" value='<?php echo json_encode(round($totalDowntime, 2)); ?>'>
<script>
    document.addEventListener('click', () => checkSession());
    document.addEventListener('input', () => checkSession());
</script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="js/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
<script src="js/majorette/chart-script.js"></script>
<script src="js/majorette/chart-script-dt.js"></script>
<script src="js/simple-datatables@latest" type="text/javascript"></script>
<script src="js/datatables/datatables-staff.js"></script>
<script src="js/litepicker/dist/bundle.js"></script>
<script src="js/litepicker.js"></script>
<script src="js/majorette/pp-time-stamp.js"></script>
</body>
</html>
