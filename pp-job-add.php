<?php

require 'pp-session-start.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจากฟอร์มและ trim เพื่อลบช่องว่าง
    $id_job = trim($_POST['id_job']);
    $work_order = trim($_POST['work_order']);
    $sales_job = trim($_POST['sales_job']);
    $prod_line = trim($_POST['prod_line']);
    $item_no = trim($_POST['item_no']);
    $item_des = trim($_POST['item_des']);
    $mold = trim($_POST['mold']);
    $qty_per_pulse2 = trim($_POST['qty_per_pulse2']);
    $site = trim($_POST['site']);
    $type = trim($_POST['type']);
    $work_center = trim($_POST['work_center']);
    $machine = trim($_POST['machine']);
    $operation = filter_var($_POST['operation'], FILTER_VALIDATE_INT);
    $color = filter_var($_POST['color'], FILTER_VALIDATE_INT);
    $op_des = trim($_POST['op_des']);
    $side = trim($_POST['side']);
    $qty_order = filter_var($_POST['qty_order'], FILTER_VALIDATE_INT);
    $qty_comp = 0; // เริ่มต้นเป็น 0 เพราะเป็นงานใหม่
    $date_due_job = trim($_POST['date_due_job']);

    // ตรวจสอบว่าฟิลด์ที่จำเป็นทั้งหมดมีค่า
    if (empty($id_job) || empty($work_order) || empty($sales_job) || empty($prod_line) || empty($item_no) ||
        empty($item_des) || empty($mold) || empty($qty_per_pulse2) || empty($site) || empty($type) ||
        empty($work_center) || empty($machine) || $operation === false || $color === false || empty($op_des) ||
        empty($side) || $qty_order === false || empty($date_due_job)) {
        echo "<script>alert('กรุณากรอกข้อมูลให้ครบถ้วน'); window.history.back();</script>";
        exit;
    }

    // ✅ เตรียมคำสั่ง SQL สำหรับการเพิ่มข้อมูล
    $sql = "INSERT INTO planning (id_job, work_order, sales_job, prod_line, item_no, item_des, mold, qty_per_pulse2, site,
                      type, work_center, machine, operation, op_color, op_des, op_side, qty_order, qty_comp, date_due) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo "<script>alert('Error in prepare statement: " . $conn->error . "'); window.history.back();</script>";
        exit;
    }

    // ✅ Bind parameters
    $stmt->bind_param(
        "sssssssisisssisssis",
        $id_job,
        $work_order,
        $sales_job,
        $prod_line,
        $item_no,
        $item_des,
        $mold,
        $qty_per_pulse2,
        $site,
        $type,
        $work_center,
        $machine,
        $operation,
        $color,
        $op_des,
        $side,
        $qty_order,
        $qty_comp,
        $date_due_job
    );

    // ✅ Execute และตรวจสอบความสำเร็จ
    if ($stmt->execute()) {
        // ✅ บันทึกลง History Log พร้อมรายละเอียด
        $username = $_SESSION['username'];

        // 🔹 รายละเอียดของ Job ที่เพิ่มใหม่
        $jobDetails = json_encode([
            "Job ID: " . $id_job,
            "Work Order: " . $work_order,
            "Sales Job: " . $sales_job,
            "Production Line: " . $prod_line,
            "Item No: " . $item_no,
            "Item Description: " . $item_des,
            "Mold: " . $mold,
            "Qty Per Pulse: " . $qty_per_pulse2,
            "Site: " . $site,
            "Type: " . $type,
            "Work Center: " . $work_center,
            "Machine: " . $machine,
            "Operation: " . $operation,
            "Color: " . $color,
            "Operation Description: " . $op_des,
            "Side: " . $side,
            "Qty Order: " . $qty_order,
            "Qty Completed: " . $qty_comp,
            "Due Date: " . $date_due_job
        ], JSON_UNESCAPED_UNICODE);

        // 🔹 Action ที่จะบันทึกใน `history`
        $action = "สร้าง job ด้วย Job ID:  $id_job ";

        // 🔹 Insert ลง `history`
        $log_sql = "INSERT INTO history (username, action, details, date_time) VALUES (?, ?, ?, NOW())";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param("sss", $username, $action, $jobDetails);
        $log_stmt->execute();
        $log_stmt->close();

        echo "<script>alert('Job added successfully!'); window.location.href='pp-machine-list-task.php';</script>";
    } else {
        echo "<script>alert('Failed to add job: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Add New Job</title>
    <link href="css/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/litepicker/dist/css/litepicker.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/employee.png" />
    <script data-search-pseudo-elements defer src="js/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="js/feather-icons/4.28.0/feather.min.js"></script>
</head>
<style>
    /* เอฟเฟกต์ปุ่ม Confirm (สีน้ำเงิน) */
    .form-button-submit {
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
    .form-button-submit:hover {
        transform: scale(1.1);
        opacity: 0.9;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        background-color: #0069d9; /* สีน้ำเงินเข้มขึ้น */
    }

    /* เอฟเฟกต์ตอนกดปุ่ม Confirm */
    .form-button-submit:active {
        transform: scale(0.95);
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.15);
        background-color: #0056b3;
    }


    /* ปุ่มใน Dark Mode */
    body.dark-mode .form-button-submit {
        background-color: #375a7f !important; /* สีฟ้าหม่น */
        color: #ffffff !important; /* สีข้อความขาว */
        border: 1px solid #444444 !important; /* เส้นขอบเข้ม */
    }
    /* Hover ใน Dark Mode */
    body.dark-mode .form-button-submit:hover {
        background-color: #3b566a !important; /* สีฟ้าหม่นเข้มขึ้นเมื่อ hover */
    }

    /* เอฟเฟกต์ปุ่ม Close (สีม่วง) */
    .form-button-reset {
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
    }

    /* เมื่อเมาส์ไปชี้ที่ปุ่ม Close */
    .form-button-reset:hover {
        transform: scale(1.1);
        opacity: 0.9;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        background-color: #d32f2f;
    }

    /* เอฟเฟกต์ตอนกดปุ่ม Close */
    .form-button-reset:active {
        transform: scale(0.95);
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.15);
        background-color: #a52020;
    }
    /* ปุ่มใน Dark Mode */
    body.dark-mode .form-button-reset {
        background-color:#A52A2A !important; /* สีม่วงหม่น */
        border-color: #A52A2A !important; /* สีเส้นขอบ */
        color: white !important; /* สีตัวอักษร */
    }

    /* Hover ใน Dark Mode */
    body.dark-mode .form-button-reset:hover {
        background-color: #8c2424 !important; /* สีม่วงหม่น */
    }
</style>
<body class="nav-fixed">
<?php require 'pp-staff-sidenavAccordion.php'; ?>
<div id="layoutSidenav">
    <?php require 'pp-layoutSidenav_nav.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-xl px-4 mt-5">
                <div class="card mb-4">
                    <div class="card-header bg-red text-white">Add New Job</div>
                    <div class="card-body">
                        <form method="post" action="pp-job-add.php">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="id_job">Job ID</label>
                                    <input class="form-control" id="id_job" name="id_job" type="text" required />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="work_order">Work Order</label>
                                    <input class="form-control" id="work_order" name="work_order" type="text" required />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="sales_job">Sale/Job</label>
                                    <input class="form-control" id="sales_job" name="sales_job" type="text" required />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="prod_line">Production Line</label>
                                    <input class="form-control" id="prod_line" name="prod_line" type="text" required />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="item_no">Item No.</label>
                                    <input class="form-control" id="item_no" name="item_no" type="text" required />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="item_des">Item Description</label>
                                    <input class="form-control" id="item_des" name="item_des" type="text" required />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="mold">Mold</label>
                                    <input class="form-control" id="mold" name="mold" type="text" required />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="qty_per_pulse2">Piece/Tray</label>
                                    <input class="form-control" id="qty_per_pulse2" name="qty_per_pulse2" type="number" required />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="site">Site</label>
                                    <input class="form-control" id="site" name="site" type="text" required />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="type">Job Type</label>
                                    <input class="form-control" id="type" name="type" type="text" required />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="work_center">Work Center</label>
                                    <input class="form-control" id="work_center" name="work_center" type="text" required />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="machine">Machine Type</label>
                                    <input class="form-control" id="machine" name="machine" type="text" required />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="operation">Operation</label>
                                    <input class="form-control" id="operation" name="operation" type="number" required />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="color">Color</label>
                                    <input class="form-control" id="color" name="color" type="number" required />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="op_des">Operation Description</label>
                                    <input class="form-control" id="op_des" name="op_des" type="text" required />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="side">Side</label>
                                    <input class="form-control" id="side" name="side" type="text" required />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="qty_order">Qty Ordered</label>
                                    <input class="form-control" id="qty_order" name="qty_order" type="number" required />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="date_due_job">Due Date</label>
                                    <input class="form-control" id="date_due_job" name="date_due_job" type="date" required />
                                </div>
                            </div>
                            <button class="btn form-button-submit" type="submit">Add Job</button>
                            <a href="pp-machine-list-task.php" class="btn form-button-reset">Cancel</a>
                        </form>
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
