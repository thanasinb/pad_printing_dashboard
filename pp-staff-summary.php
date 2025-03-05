<?php
require 'pp-session-start.php';
ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require 'update/establish.php';

// ดึงจำนวนพนักงานทั้งหมด
$sql_total_staff = "SELECT COUNT(*) AS total_staff FROM staff WHERE active = 1";
$total_staff_result = $conn->query($sql_total_staff);
$total_staff = ($total_staff_result->fetch_assoc())['total_staff'];

// ดึงจำนวนพนักงานแยกตาม role
$sql_role_counts = "
    SELECT role.role, COUNT(staff.id_staff) AS staff_count
    FROM staff
    JOIN role ON staff.id_role = role.id_role
    WHERE staff.active = 1
    GROUP BY role.role
    ORDER BY staff_count DESC";
$role_counts_result = $conn->query($sql_role_counts);

$role_counts = [];
while ($row = $role_counts_result->fetch_assoc()) {
    $role_counts[] = $row;
}

function getLinkForRole($role) {
    switch ($role) {
        case 'Manager':
        case 'Engineering':
            return "pp-staff-admin.php?role=" . urlencode($role);
        case 'Foreman':
            return "pp-staff-foreman.php?role=" . urlencode($role);
        case 'Technician':
        case 'Senior Technician':
            return "pp-staff-technician.php?role=" . urlencode($role);
        case 'Operator':
        case 'Instructor':
        case 'Senior Instructor':
        case 'Leader':
        case 'Production Support':
            return "pp-staff-operator.php?role=" . urlencode($role);
        default:
            return "#"; // ถ้า role อื่นยังไม่มีหน้าให้ลิงก์ จะไม่ทำอะไร
    }
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
        <title>Staff Summary</title>
        <link href="css/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="css/litepicker/dist/css/litepicker.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="assets/img/multiple-users-silhouette.png" />
        <script data-search-pseudo-elements defer src="js/font-awesome/5.15.3/js/all.min.js"></script>
        <script src="js/feather-icons/4.28.0/feather.min.js"></script>
        <link rel="stylesheet" href="css/reorder-columns/dragtable.css">
        <link rel="stylesheet" href="css/reorder-columns/bootstrap-table.min.css">
        <link rel="stylesheet" href="css/majorette.css">
        <script src="js/jquery/jquery.min.js"></script>
        <script src="js/jquery/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="css/pp-staff-summary.css">

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
                <div class="container-fluid px-4 mt-n10">
                    <div class="card mb-4 w-100">
                        <div class="card-header bg-red fw-bold text-white fs-4">Staff Summary</div>
                        <div class="card-body">
                            <!-- Main content -->
                            <div class="card mb-4 p-4">
                                <h3 class="staff-summary-title">จำนวนพนักงาน</h3>
                                <p><strong>พนักงานทั้งหมด:</strong> <?php echo $total_staff; ?> คน</p>
                                <ul class="staff-list">
                                    <?php foreach ($role_counts as $role): ?>
                                        <li>
                                            <a href="<?php echo getLinkForRole($role['role']); ?>"
                                               class="staff-role">
                                                <?php echo $role['role']; ?>
                                            </a>
                                            <span class="staff-count">: <?php echo $role['staff_count']; ?> คน</span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="js/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/simple-datatables@latest" type="text/javascript"></script>
    <script src="js/datatables/datatables-staff.js"></script>
    <script src="js/litepicker/dist/bundle.js"></script>
    <script src="js/litepicker.js"></script>
    <script type="text/javascript" src="js/majorette/pp-session.js"></script>
    </body>
    </html>
<?php require 'update/terminate.php'; ?>