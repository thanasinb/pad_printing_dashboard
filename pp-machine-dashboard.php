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
    <link rel="stylesheet" href="css/majorette.css">
    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/jquery/jquery-ui.min.js"></script>
    <script src="js/scripts.js"></script>
<!--    <script type="text/javascript" src="js/majorette/pp-machine-currentTaskModal.js"></script>-->
<!--    <script type="text/javascript" src="js/majorette/pp-machine-refresh-2.js"></script>-->
<!--    <script type="text/javascript" src="js/majorette/pp-machine-clock.js"></script>-->
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
                <div class="row">
                    <div class="col-xxl-4 col-xl-12 mb-4">
                        <div class="card h-100">
                            <div class="card-body h-100 p-5">
                                <div class="row align-items-center">
                                    <div class="col-xl-8 col-xxl-12">
                                        <div class="text-center text-xl-start text-xxl-center mb-4 mb-xl-0 mb-xxl-4">
                                            <h1 class="text-primary">M/C 02-01</h1>
                                            <p class="text-gray-700 mb-0">Pad printer 2C</p>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-xxl-12 text-center"><img src="images/Machine.jpg" style="max-width: 26rem" /></div>
                                </div>
                            </div>
                            <div class="card-footer position-relative">
                                <div class="d-flex align-items-center justify-content-between small text-body">
                                    <a class="stretched-link text-body" href="#!">Edit info</a>
                                    <i class="fas fa-angle-right"></i>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-xxl-4 col-xl-6 mb-4">
                        <div class="card card-header-actions h-100">
                            <div class="card-header">
                                Recent Activity
                                <div class="dropdown no-caret">
                                    <button class="btn btn-transparent-dark btn-icon dropdown-toggle" id="dropdownMenuButton" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="text-gray-500" data-feather="more-vertical"></i></button>
                                    <div class="dropdown-menu dropdown-menu-end animated--fade-in-up" aria-labelledby="dropdownMenuButton">
                                        <h6 class="dropdown-header">Filter Activity:</h6>
                                        <a class="dropdown-item" href="#!"><span class="badge bg-green-soft text-green my-1">Run</span></a>
                                        <a class="dropdown-item" href="#!"><span class="badge bg-yellow-soft text-yellow my-1">Pause</span></a>
                                        <a class="dropdown-item" href="#!"><span class="badge bg-red-soft text-red my-1">Down</span></a>
                                        <a class="dropdown-item" href="#!"><span class="badge bg-purple-soft text-purple my-1">Disconnect</span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="timeline timeline-xs">
                                    <!-- Timeline Item 1-->
                                    <div class="timeline-item">
                                        <div class="timeline-item-marker">
                                            <div class="timeline-item-marker-text">27 min</div>
                                            <div class="timeline-item-marker-indicator bg-green"></div>
                                        </div>
                                        <div class="timeline-item-content">
                                            Resume
                                            <a class="fw-bold text-dark" href="#!">Job ID: 5648523</a><br>
                                            <a class="fw-bold text-dark" href="#!">Part NO: 200237F80208, OP: C ไฟ</a>
                                        </div>
                                    </div>
                                    <!-- Timeline Item 2-->
                                    <div class="timeline-item">
                                        <div class="timeline-item-marker">
                                            <div class="timeline-item-marker-text">58 min</div>
                                            <div class="timeline-item-marker-indicator bg-yellow"></div>
                                        </div>
                                        <div class="timeline-item-content">
                                            Break
                                        </div>
                                    </div>
                                    <!-- Timeline Item 3-->
                                    <div class="timeline-item">
                                        <div class="timeline-item-marker">
                                            <div class="timeline-item-marker-text">2 hrs</div>
                                            <div class="timeline-item-marker-indicator bg-green"></div>
                                        </div>
                                        <div class="timeline-item-content">
                                            Run
                                            <a class="fw-bold text-dark" href="#!">Job ID: 5648523</a><br>
                                            <a class="fw-bold text-dark" href="#!">Part NO: 200237F80208, OP: C ไฟ</a>
                                        </div>
                                    </div>
                                    <!-- Timeline Item 4-->
                                    <div class="timeline-item">
                                        <div class="timeline-item-marker">
                                            <div class="timeline-item-marker-text">1 day</div>
                                            <div class="timeline-item-marker-indicator bg-red"></div>
                                        </div>
                                        <div class="timeline-item-content">
                                            Setup
                                            <a class="fw-bold text-dark" href="#!">Job ID: 5648523</a><br>
                                            <a class="fw-bold text-dark" href="#!">Part NO: 200237F80208, OP: C ไฟ</a>
                                        </div>
                                    </div>
                                    <!-- Timeline Item 5-->
                                    <div class="timeline-item">
                                        <div class="timeline-item-marker">
                                            <div class="timeline-item-marker-text">1 day</div>
                                            <div class="timeline-item-marker-indicator bg-red"></div>
                                        </div>
                                        <div class="timeline-item-content">Machine down</div>
                                    </div>
                                    <!-- Timeline Item 6-->
                                    <div class="timeline-item">
                                        <div class="timeline-item-marker">
                                            <div class="timeline-item-marker-text">1 day</div>
                                            <div class="timeline-item-marker-indicator bg-purple"></div>
                                        </div>
                                        <div class="timeline-item-content">
                                            WiFi disconnected
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-4 col-xl-6 mb-4">
                        <div class="card card-header-actions h-100">
                            <div class="card-header">
                                Current Job
                                <div class="dropdown no-caret">
                                    <button class="btn btn-transparent-dark btn-icon dropdown-toggle" id="dropdownCurrentJob" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="text-gray-500" data-feather="more-vertical"></i></button>
                                    <div class="dropdown-menu dropdown-menu-end animated--fade-in-up" aria-labelledby="dropdownCurrentJob">
                                        <a class="dropdown-item" href="#!">
                                            <div class="dropdown-item-icon"><i class="text-gray-500" data-feather="edit"></i></div>
                                            Edit current job
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <h4 class="small">
                                    Job ID: 5648523 <br>
                                    Part NO: 200237F80208, OP: C ไฟ
                                    <span class="float-end fw-bold">60%</span>
                                </h4>
                                <div class="progress mb-4"><div class="progress-bar" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div></div>
                                <h4 class="small">
                                    <?php
                                    require 'update/establish.php';
                                    $sql = "SELECT id_wip,contact_date,contact_time,contact_count FROM machine WHERE id_mc='02-01'";
                                    $result = $conn->query($sql);
                                    $row = $result->fetch_assoc();
                                    echo "Contact date:" . $row["contact_date"] . "<br>";
                                    echo "Contact time:" . $row["contact_time"] . "<br>";
                                    echo "Contact count:" . $row["contact_count"] . "<br>";

                                    $sql = "SELECT qty_process,qty_accum FROM wip_backflush WHERE id_wip_backflush=" . $row["id_wip"];
                                    $result = $conn->query($sql);
                                    $row = $result->fetch_assoc();
                                    echo "Qty process:" . $row["qty_process"] . "<br>";
                                    echo "Qty accum:" . $row["qty_accum"];

                                    require 'update/terminate.php';
                                    ?>
                                </h4>
                            </div>
                            <div class="card-header">
                                Next Job
                                <div class="dropdown no-caret">
                                    <button class="btn btn-transparent-dark btn-icon dropdown-toggle" id="dropdownNextJob" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="text-gray-500" data-feather="more-vertical"></i></button>
                                    <div class="dropdown-menu dropdown-menu-end animated--fade-in-up" aria-labelledby="dropdownNextJob">
                                        <a class="dropdown-item" href="#!">
                                            <div class="dropdown-item-icon"><i class="text-gray-500" data-feather="edit"></i></div>
                                            Edit next job
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <h4 class="small">
                                    Job ID: 5648524 <br>
                                    Part NO: 200209H23509, OP: A 45
                                </h4>
                            </div>
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
