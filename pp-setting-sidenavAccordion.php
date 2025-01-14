<?php
require 'pp-session.php';
require 'update/establish.php'; // เชื่อมต่อฐานข้อมูล

// ดึงชื่อวันในสัปดาห์ (เช่น Sunday, Monday, ...)
$dayOfWeek = date('l');
$currentDate = date('F j, Y'); // วันที่ปัจจุบัน (เช่น January 12, 2025)

// ดึงข้อความแจ้งเตือนที่ตรงกับวันในสัปดาห์
$sql = "SELECT message FROM daily_alerts WHERE day_of_week = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $dayOfWeek); // Bind ชื่อวัน เช่น "Sunday"
$stmt->execute();
$result = $stmt->get_result();

// กำหนดการแสดงผลข้อความแจ้งเตือนเริ่มต้น
$alert_messages = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $alert_messages[] = [
            'message' => $row['message'],
            'date' => $currentDate // ใช้วันที่ปัจจุบัน
        ];
    }
} else {
    $alert_messages[] = [
        'message' => "ไม่มีข้อความแจ้งเตือนสำหรับวันนี้.",
        'date' => $currentDate // ใช้วันที่ปัจจุบัน
    ]; // กรณีไม่มีข้อความ
}

// ตรวจสอบว่าผู้ใช้ล็อกอินอยู่
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // ดึงชื่อไฟล์ภาพโปรไฟล์จากฐานข้อมูล
    $sql = "SELECT profile_image FROM staff WHERE id_staff = (SELECT id_staff FROM login WHERE username = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // ตรวจสอบว่ามีภาพโปรไฟล์หรือไม่
    if ($row && !empty($row['profile_image'])) {
        $profileImagePath = "uploads/" . $row['profile_image'];
    } else {
        // หากไม่มีภาพที่อัปโหลด ให้แสดงภาพเริ่มต้น
        $profileImagePath = "assets/img/illustrations/profiles/profile-1.png";
    }
}
$conn->close(); // ปิดการเชื่อมต่อฐานข้อมูล
?>
<link rel="stylesheet" href="css/pp-sidenav.css">

<nav class="topnav navbar navbar-expand shadow justify-content-between justify-content-sm-start navbar-light bg-white" id="sidenavAccordion">
    <!-- Sidenav Toggle Button-->
    <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 me-2 ms-lg-2 me-lg-0" id="sidebarToggle"><i data-feather="menu"></i></button>
    <a class="navbar-brand pe-3 ps-4 ps-lg-2" href="pp-machine-3.php">Majorette</a>

    <!-- Navbar Search Input-->
    <form class="form-inline me-auto d-none d-lg-block me-3">
        <div class="input-group input-group-joined input-group-solid">
            <input class="form-control pe-0" id="menuSearchInput" type="search" placeholder="Search" aria-label="Search" />
            <script>
                $(document).ready(function() {
                    $('#menuSearchInput').on('keyup', function() {
                        var searchText = $(this).val().toLowerCase();
                        $('.sidenav-menu a.nav-link').each(function() {
                            var menuItemText = $(this).text().toLowerCase();
                            if (menuItemText.includes(searchText)) {
                                $(this).show();
                            } else {
                                $(this).hide();
                            }
                        });
                    });
                });
            </script>
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

        <!-- Dark Mode Toggle -->
        <li class="nav-item ms-3">
            <button id="darkModeToggle" class="btn btn-icon">
                <i id="darkModeIcon" class="fas fa-moon"></i> <!-- Default icon -->
            </button>
        </li>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const darkModeToggle = document.getElementById("darkModeToggle");
                const darkModeIcon = document.getElementById("darkModeIcon");
                const body = document.body;

                // ฟังก์ชันสำหรับตั้งค่ารูปไอคอน
                function updateDarkModeIcon() {
                    if (body.classList.contains("dark-mode")) {
                        darkModeIcon.classList.remove("fa-moon");
                        darkModeIcon.classList.add("fa-sun");
                    } else {
                        darkModeIcon.classList.remove("fa-sun");
                        darkModeIcon.classList.add("fa-moon");
                    }
                }

                // ตรวจสอบว่าผู้ใช้เคยเปิดโหมดมืดไว้หรือไม่
                if (localStorage.getItem("darkMode") === "enabled") {
                    body.classList.add("dark-mode");
                }
                updateDarkModeIcon(); // อัปเดตรูปไอคอน

                // Event Listener สำหรับปุ่ม
                darkModeToggle.addEventListener("click", function () {
                    if (body.classList.contains("dark-mode")) {
                        body.classList.remove("dark-mode");
                        localStorage.setItem("darkMode", "disabled"); // บันทึกสถานะ
                    } else {
                        body.classList.add("dark-mode");
                        localStorage.setItem("darkMode", "enabled"); // บันทึกสถานะ
                    }
                    updateDarkModeIcon(); // อัปเดตรูปไอคอนเมื่อสลับโหมด
                });
            });
        </script>
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
        <style>
            .dropdown-user-details-role {
                font-size: 12px;
                color: gray;
            }
            .dropdown-user-details-role1 {
                font-size: 12px;
                color: black;
            }
        </style>
        <li class="nav-item dropdown no-caret dropdown-user me-3 me-lg-4">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img id="sidenavUserImage" class="img-fluid" src="<?php echo $profileImagePath; ?>" alt="User Image" />
            </a>
            <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">
                <h6 class="dropdown-header d-flex align-items-center">
                    <img id="dropdownUserImage" class="dropdown-user-img" src="<?php echo $profileImagePath; ?>" alt="Dropdown User Image" />
                    <div class="dropdown-user-details">
                        <div class="dropdown-user-details-name">
                            <?php echo $name . " " . $surname; ?>
                        </div>
                        <div class="dropdown-user-details-role">
                            Logged in as Role Group: <?php echo $role_group; ?><br>
                        </div>
                        <div class="dropdown-user-details-role1">
                            Role: <?php echo $role; ?>
                        </div>
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
            </div>
        </li>
    </ul>
</nav>