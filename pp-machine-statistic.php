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
    <title>Machine Statistic</title>
    <link href="css/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/litepicker/dist/css/litepicker.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-tooltip"></script>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script data-search-pseudo-elements defer src="js/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="js/feather-icons/4.28.0/feather.min.js"></script>
    <link rel="stylesheet" href="css/reorder-columns/dragtable.css">
    <link rel="stylesheet" href="css/reorder-columns/bootstrap-table.min.css">
    <link rel="stylesheet" href="css/majorette.css">
    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/jquery/jquery-ui.min.js"></script>
    <style>
        .chart-container {
            width: 1200px;
            height: 500px;
            overflow-x: auto;
        }
        .chartjs-tooltip {
            max-height: 150px;
            overflow-y: auto;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            border-radius: 3px;
            padding: 10px;
            pointer-events: none;
            position: absolute;
            transform: translate(-50%, 0);
            transition: all .1s ease;
            z-index: 9999;
        }
        .modal-body {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-dialog {
            max-width: 25%; /* ตั้งค่าให้ modal dialog มีความกว้างไม่เกิน 80% ของหน้าจอ */
        }
    </style>
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
            <div class="container-xxl px-4 mt-n10">
                <div class="row">
                    <div class="container-xxl-8">
                        <!-- Tabbed dashboard card example-->
                        <div class="card mb-4">
                            <div class="card-header bg-red fw-bold text-white fs-4 d-flex justify-content-between">
                                <div>Machine Statistic</div>
                                <div>
                                    <span id="hours"></span> :
                                    <span id="minutes"></span> :
                                    <span id="seconds"></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="dashboardNavContent">
                                    <!-- Dashboard Tab Pane 1-->
                                    <div class="tab-pane fade show active " id="overview" role="tabpanel" aria-labelledby="overview-pill" style="width: 100%; white-space: nowrap">
                                        <div class="chart-container">
                                            <?php require 'pp-mc-get-to-chart.php'; ?>
                                            <canvas id="machineChart"></canvas>
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
        <footer class="footer-admin mt-auto footer-light">
            <div class="container-xl px-4">
                <div class="row">
                    <div class="col-md-6 small">Copyright &copy; Your Website 2021</div>
                    <div class="col-md-6 text-md-end small">
                        <a href="#!">Privacy Policy</a>
                        &middot;
                        <a href="#!">Terms &amp; Conditions</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>
<!---->
<!--<script>-->
<!--    --><?php //require 'pp-mc-get-to-chart.php'; ?>
//    document.addEventListener('DOMContentLoaded', function () {
//        const ctx = document.getElementById('machineChart').getContext('2d');
//
//        const machines = <?php //echo $machines_json; ?>//;
//        const jobCounts = <?php //echo $jobCounts_json; ?>//;
//        const taskDetails = <?php //echo $task_details_json; ?>//;
//
//        const machineData = {
//            labels: machines,
//            datasets: [{
//                label: 'Total of Jobs',
//                data: jobCounts,
//                backgroundColor: 'rgba(255, 99, 132, 0.2)',
//                borderColor: 'rgba(255, 99, 132, 1)',
//                borderWidth: 1
//            }]
//        };
//
//        const machineChart = new Chart(ctx, {
//            type: 'bar',
//            data: machineData,
//            options: {
//                onClick: function (evt, elements) {
//                    if (elements.length > 0) {
//                        const element = elements[0];
//                        const index = element.index;
//                        const machine = machines[index];
//                        const jobCount = jobCounts[index];
//                        const tasks = taskDetails[index].map(task => `${task}`).join('<br>');
//
//                        const modalContent = `
//                        <strong>Machine:</strong> ${machine}<br>
//                        <strong>Total of Jobs:</strong> ${jobCount}<br>
//                        <strong>ID Tasks:</strong><br> ${tasks}
//                    `;
//
//                        document.getElementById('modalContent').innerHTML = modalContent;
//                        $('#detailModal').modal('show');
//                    }
//                },
//                scales: {
//                    y: {
//                        beginAtZero: true,
//                        title: {
//                            display: true,
//                            text: 'Total of Jobs'
//                        }
//                    },
//                    x: {
//                        title: {
//                            display: true,
//                            text: 'Machines'
//                        },
//                        ticks: {
//                            autoSkip: false,
//                            maxRotation: 90,
//                            minRotation: 90
//                        }
//                    }
//                }
//            }
//        });
//    });


<script src="js/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
<script src="js/simple-datatables@latest" type="text/javascript"></script>
<script src="js/datatables/datatables-staff.js"></script>
<script src="js/litepicker/dist/bundle.js"></script>
<script src="js/litepicker.js"></script>
<script src="js/majorette/pp-time-stamp.js"></script>
<script src="js/Chart.js/chart-mc-overall.js"></script>
<script type="text/javascript" src="js/majorette/pp-session.js"></script>

</body>
</html>
