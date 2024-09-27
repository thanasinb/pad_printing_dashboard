<?php
require 'pp-session.php';
require 'update/establish.php'; // เชื่อมต่อฐานข้อมูล

// ดึงข้อมูลการแจ้งเตือนจากฐานข้อมูล
$dayOfWeek = date('l'); // ได้ค่าเป็นเช่น Monday, Tuesday
$sql = "SELECT message, created_at FROM daily_alerts WHERE day_of_week = '$dayOfWeek'";
$result = $conn->query($sql);

// กำหนดการแสดงผลข้อความแจ้งเตือนเริ่มต้น
$alert_messages = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $alert_messages[] = [
            'message' => $row['message'],
            'date' => date('F j, Y', strtotime($row['created_at']))
        ]; // เก็บข้อความและวันที่ลงใน array
    }
} else {
    $alert_messages[] = [
        'message' => "No alerts for today.",
        'date' => date('F j, Y')
    ]; // กรณีไม่มีข้อมูล
}

$conn->close(); // ปิดการเชื่อมต่อฐานข้อมูล
?>
<nav class="topnav navbar navbar-expand shadow justify-content-between justify-content-sm-start navbar-light bg-white" id="sidenavAccordion">
    <!-- Sidenav Toggle Button-->
    <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 me-2 ms-lg-2 me-lg-0" id="sidebarToggle"><i data-feather="menu"></i></button>
    <a class="navbar-brand pe-3 ps-4 ps-lg-2" href="pp-machine-3.php">Majorette</a>

    <!-- Navbar Search Input-->
    <form class="form-inline me-auto d-none d-lg-block me-3">
        <div class="input-group input-group-joined input-group-solid">
            <input class="form-control pe-0" id="menuSearchInput" type="search" placeholder="Search" aria-label="Search" />
            <div class="input-group-text"><i data-feather="search"></i></div>
        </div>
    </form>

    <!-- Navbar Items-->
    <ul class="navbar-nav align-items-center ms-auto">
        <li class="nav-item ms-3">
            <p class="nav-link mb-0">
                <span id="currentDateTime"></span>
            </p>
        </li>

        <script>
            function updateDateTime() {
                var now = new Date();
                var dateTimeString = now.toLocaleString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: 'numeric',
                    minute: 'numeric',
                    second: 'numeric',
                    hour12: true
                });

                document.getElementById('currentDateTime').textContent = dateTimeString;
            }

            updateDateTime();
            setInterval(updateDateTime, 1000);
        </script>

        <!-- Alerts Dropdown-->
        <li class="nav-item dropdown no-caret d-none d-sm-block me-3 dropdown-notifications">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownAlerts" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="bell"></i></a>
            <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownAlerts">
                <h6 class="dropdown-header dropdown-notifications-header">
                    <i class="me-2" data-feather="bell"></i>
                    Alerts Center
                </h6>
                <!-- Display Alerts from the database -->
                <?php foreach ($alert_messages as $alert): ?>
                    <a class="dropdown-item dropdown-notifications-item" href="#!">
                        <div class="dropdown-notifications-item-icon bg-warning"><i data-feather="activity"></i></div>
                        <div class="dropdown-notifications-item-content">
                            <div class="dropdown-notifications-item-content-details"><?php echo $alert['date']; ?></div>
                            <div class="dropdown-notifications-item-content-text"><?php echo $alert['message']; ?></div>
                        </div>
                    </a>
                <?php endforeach; ?>
                <a class="dropdown-item dropdown-notifications-footer" href="#!">View All Alerts</a>
            </div>
        </li>

        <!-- Messages Dropdown-->
        <li class="nav-item dropdown no-caret d-none d-sm-block me-3 dropdown-notifications">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownMessages" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="mail"></i></a>
            <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownMessages">
                <h6 class="dropdown-header dropdown-notifications-header">
                    <i class="me-2" data-feather="mail"></i>
                    Message Center
                </h6>
                <!-- Example Message 1  -->
                <a class="dropdown-item dropdown-notifications-item" href="#!">
                    <img class="dropdown-notifications-item-img" src="assets/img/illustrations/profiles/profile-2.png" />
                    <div class="dropdown-notifications-item-content">
                        <div class="dropdown-notifications-item-content-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</div>
                        <div class="dropdown-notifications-item-content-details">Thomas Wilcox · 58m</div>
                    </div>
                </a>
                <!-- Example Message 2-->
                <a class="dropdown-item dropdown-notifications-item" href="#!">
                    <img class="dropdown-notifications-item-img" src="assets/img/illustrations/profiles/profile-3.png" />
                    <div class="dropdown-notifications-item-content">
                        <div class="dropdown-notifications-item-content-text">Lorem ipsum dolor sit amet.</div>
                        <div class="dropdown-notifications-item-content-details">Emily Fowler · 2d</div>
                    </div>
                </a>
                <a class="dropdown-item dropdown-notifications-footer" href="#!">Read All Messages</a>
            </div>
        </li>

        <!-- User Dropdown-->
        <li class="nav-item dropdown no-caret dropdown-user me-3 me-lg-4">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img class="img-fluid" src="assets/img/illustrations/profiles/profile-1.png" /></a>
            <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">
                <h6 class="dropdown-header d-flex align-items-center">
                    <img class="dropdown-user-img" src="assets/img/illustrations/profiles/profile-1.png" />
                    <div class="dropdown-user-details">
                        <div class="dropdown-user-details-name"><?php echo $name, " ", $surname; ?></div>
                    </div>
                </h6>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="pp-account.php">
                    <div class="dropdown-item-icon"><i data-feather="settings"></i></div>
                    Account
                </a>

                <a class="dropdown-item" href="pp-logout.php" onclick="confirmLogout()">
                    <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>
                    Logout
                </a>
                <script src="js/logoutpopup.js"></script>
            </div>
        </li>
    </ul>
</nav>