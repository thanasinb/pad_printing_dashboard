<?php
?>
<link href="css/center.css" rel="stylesheet">
<nav class="topnav navbar navbar-expand shadow justify-content-between justify-content-sm-start navbar-light bg-white" id="sidenavAccordion">
    <!-- ใช้ไอคอน FontAwesome เพื่อแสดงเป็นปุ่ม "Login" -->
    <style>
        /* เลือกปุ่มด้วย ID หรือ Class ของปุ่ม */
        #sidebarToggle {
            width: 50px; /* กำหนดความกว้างของปุ่ม */
            height: 50px; /* กำหนดความสูงของปุ่ม */
            border-radius: 50%; /* กำหนดรูปร่างของปุ่มให้เป็นวงกลม */
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: black; /* กำหนดสีพื้นหลังของปุ่มเป็นสีฟ้า */
            border: none;
            cursor: pointer;

        }
        #sidebarToggle {
            font-weight: normal; /* กำหนดความหนาของข้อความเป็นปกติ */
            font-style: normal;
        }
        /* สไตล์ข้อความภายในปุ่ม */
        #sidebarToggle i {
            color: white; /* กำหนดสีข้อความเป็นสีขาว */
            font-size: 12px; /* กำหนดขนาดตัวอักษรของข้อความ */

        }
    </style>
    <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 me-2 ms-lg-4 me-lg-0" id="sidebarToggle">
        <i >Login</i>
    </button>
    <script>
        // เลือกปุ่มโดยใช้ ID
        const loginButton = document.getElementById('sidebarToggle');

        // เพิ่ม event listener เมื่อคลิกปุ่ม
        loginButton.addEventListener('click', function() {
            // นำไปยังหน้า pp-login.php
            window.location.href = 'pp-login.php';
        });
    </script>


    <a class="navbar-brand" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">Majorette</a>

    <!-- Navbar Items-->
    <ul class="navbar-nav align-items-center ms-auto">

        <!-- User Dropdown-->
        <li class="nav-item dropdown no-caret dropdown-user me-3 me-lg-4">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img class="img-fluid" src="assets/img/illustrations/profiles/profile-user.png" /></a>
            <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">
                <h6 class="dropdown-header d-flex align-items-center">
                    <img class="dropdown-user-img" src="assets/img/illustrations/profiles/profile-user.png" />
                    <div class="dropdown-user-details">
                        <div class="dropdown-user-details-name">Valerie Luna</div>
                        <div class="dropdown-user-details-email">vluna@aol.com</div>
                    </div>
                </h6>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="pp-login.php">
                    <div class="dropdown-item-icon"><i data-feather="log-in"></i></div>
                    Login
                </a>
            </div>
        </li>
    </ul>
</nav>
