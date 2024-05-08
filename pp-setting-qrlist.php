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
    <title>QR code List</title>
    <link href="css/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/litepicker/dist/css/litepicker.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
    <script data-search-pseudo-elements defer src="js/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="js/feather-icons/4.28.0/feather.min.js"></script>
    <link rel="stylesheet" href="css/majorette.css">



    <script type="text/javascript" src="js/majorette/pp-machine-refresh-3.js"></script>
    <script type="text/javascript" src="js/majorette/pp-machine-clock.js"></script>
</head>
<body class="nav-fixed">
<?php require 'pp-setting-sidenavAccordion.php'; ?>
<?php require 'pp-session.php'; ?>
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
            <div class="container-fluid px-4 mt-n10">
                <!-- Example DataTable for Dashboard Demo-->
                <div class="card mb-4 w-100" id="table-machine">
                    <div class="card-header bg-red fw-bold text-white fs-4 d-flex justify-content-between">
                        <div>QR code List</div>
                        <div>
                            <span id="hours"></span> :
                            <span id="minutes"></span> :
                            <span id="seconds"></span>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="datatablesSimple" class="table table-striped" style="width: 100%; white-space: nowrap">
                            <thead class="text-black" style="background-color: #ffea07">
                            <?php
                            // เรียกใช้ไฟล์ pp-setting-qr-table-head.php เพื่อแสดงหัวตาราง
                            require_once 'pp-setting-qr-table-head.php';
                            ?>
                            </thead>
                            <tbody id="table_body">
                            <?php
                            require 'update/establish.php';

                            // คำสั่ง SQL เพื่อดึงข้อมูลจากตาราง qrcodes
                            $sql = "SELECT * FROM qrcodes";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                // วนลูปเพื่อแสดงข้อมูลในตาราง
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>' . $row['id'] . '</td>';
                                    echo '<td>' . $row['qr_code_value'] . '</td>';
                                    echo '<td><img src="' . $row['qr_code_image_path'] . '" width="100" height="100"></td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="3">No data found</td></tr>';
                            }

                            // ปิดการเชื่อมต่อ MySQL
                            $conn->close();
                            ?>
                            </tbody>
                        </table>
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
<script>
    function updateClock() {
        var now = new Date();
        var hours = now.getHours();
        var minutes = now.getMinutes();
        var seconds = now.getSeconds();

        hours = hours < 10 ? '0' + hours : hours;
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;

        document.getElementById('hours').textContent = hours;
        document.getElementById('minutes').textContent = minutes;
        document.getElementById('seconds').textContent = seconds;
    }

    updateClock(); // เรียกใช้ฟังก์ชัน updateClock เพื่ออัปเดตเวลาแรกครั้ง
    setInterval(updateClock, 1000); // ใช้ setInterval เพื่ออัปเดตเวลาทุกๆ 1 วินาที
</script>
</body>
</html>