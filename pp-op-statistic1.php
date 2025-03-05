<?php
require 'pp-session-start.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Operator Statistic</title>
    <link href="css/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/litepicker/dist/css/litepicker.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/multiple-users-silhouette.png" />
    <script data-search-pseudo-elements defer src="js/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="js/feather-icons/4.28.0/feather.min.js"></script>
    <link rel="stylesheet" href="css/majorette.css">
    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/jquery/jquery-ui.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- js script -->
    <script type="text/javascript" src="js/majorette/pp-account-user.js"></script>
    <script type="text/javascript" src="js/majorette/pp-account-user-add.js"></script>
    <!-- css style -->
    <link href="css/pp-account-user.css" rel="stylesheet" />

    <style>
        /* CSS สำหรับเอฟเฟกต์กระพริบสีแดง (ถ้าต้องการใช้ในหน้านี้ด้วย) */
        .overtime-blink {
            color: red;
            font-weight: bold;
            animation: blink 1s infinite;
        }
        @keyframes blink {
            0%   { opacity: 1; }
            50%  { opacity: 0; }
            100% { opacity: 1; }
        }
        .date-filter-container {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .date-filter-container label {
            font-size: 14px;
            white-space: nowrap;
        }

        .date-filter-container input {
            width: 140px; /* ปรับขนาดของ input */
            padding: 5px;
            font-size: 14px;
        }

        .date-filter-container button {
            padding: 6px 12px;
            font-size: 14px;
        }
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
            <div class="container-fluid px-4 mt-n10">
                <!-- Card for Operator Statistic Table -->
                <div class="card mb-4 w-100" id="table-machine">
                    <div class="card-header bg-red fw-bold text-white fs-4 d-flex justify-content-between">
                        <div>Operator Statistic</div>
                    </div>
                    <div class="card-body">
                        <!-- เพิ่มฟอร์มกรองวันที่ -->
                        <form method="GET" action="pp-op-statistic1.php" class="mb-3 date-filter-container">
                            <label for="start_date" class="fw-bold">Start Date:</label>
                            <input type="date" id="start_date" name="start_date" class="form-control"
                                   value="<?php echo isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : ''; ?>">

                            <label for="end_date" class="fw-bold">End Date:</label>
                            <input type="date" id="end_date" name="end_date" class="form-control"
                                   value="<?php echo isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : ''; ?>">

                            <button type="submit" class="btn btn-confirm-modal">Filter</button>
                        </form>
                        <table id="datatablesSimple" class="table table-striped" style="width: 100%; white-space: nowrap">
                            <thead class="text-black" style="background-color: #ffea07">
                            <?php require_once 'pp-op-statistic-table-head1.php'; ?>
                            </thead>
                            <tbody id="table_body">
                            <?php
                            require 'pp-op-statistic-script1.php';
                            ?>
                            </tbody>
                        </table>

                    </div>

                </div>

                <!-- Script resources -->
                <script src="js/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
                <script src="js/scripts.js"></script>
                <script src="js/simple-datatables@latest" type="text/javascript"></script>
                <script src="js/datatables/datatables-staff.js"></script>
                <script src="js/litepicker/dist/bundle.js"></script>
                <script src="js/litepicker.js"></script>
                <script type="text/javascript" src="js/majorette/pp-session.js"></script>
            </div>
        </main>
    </div>
</div>
</body>
</html>
