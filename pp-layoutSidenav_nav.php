<?php
session_start();
require 'update/establish.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: pp-login.php");
    exit();
}

// Retrieve role group and role group name from session
$id_role_group = $_SESSION['role_group'];
$role_group_name = $_SESSION['role_group_name'];
require 'update/terminate.php';
?>


<!--<script src="js/menu-logger.js"></script>-->
<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
            <div class="nav accordion" id="accordionSidenav">
                <!-- Sidenav Menu Heading (Account)-->
                <!-- * * Note: * * Visible only on and above the sm breakpoint-->
                <div class="sidenav-menu-heading d-sm-none">Account</div>
                <!-- Sidenav Link (Alerts)-->
                <!-- * * Note: * * Visible only on and above the sm breakpoint-->
                <a class="nav-link d-sm-none" href="#!">
                    <div class="nav-link-icon"><i data-feather="bell"></i></div>
                    Alerts
                    <span class="badge bg-warning-soft text-warning ms-auto">4 New!</span>
                </a>
                <!-- Sidenav Link (Messages)-->
                <!-- * * Note: * * Visible only on and above the sm breakpoint-->
                <a class="nav-link d-sm-none" href="#!">
                    <div class="nav-link-icon"><i data-feather="mail"></i></div>
                    Messages
                    <span class="badge bg-success-soft text-success ms-auto">2 New!</span>
                </a>

                <div class="sidenav-menu-heading">Menu</div>
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseMachines" aria-expanded="false" aria-controls="collapseMachines">
                    <div class="nav-link-icon"><i class="fas fa-stamp"></i></div>
                    Machines
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseMachines" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <a class="nav-link" href="pp-machine-3.php"><i class="fas fa-list" style="opacity: 0.5; margin-right: 6px;"></i>Machine List</a>
                        <a class="nav-link" href="pp-machine-statistic.php"><i class="fas fa-chart-bar" style="opacity: 0.5; margin-right: 6px;"></i>Machine Statistic</a>
                        <a class="nav-link" href="pp-machine-add.php"><i class="fas fa-plus" style="opacity: 0.5; margin-right: 6px;"></i>Add new Machine</a>
                    </nav>
                </div>
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseStaffs" aria-expanded="false" aria-controls="collapseStaffs">
                    <div class="nav-link-icon"><i class="fas fa-users"></i></div>
                    Staffs
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseStaffs" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <a class="nav-link" href="pp-staff-operator.php"><i class="fas fa-list" style="opacity: 0.5; margin-right: 6px;"></i>Operator List</a>
                        <a class="nav-link" href="pp-staff-technician.php"><i class="fas fa-list" style="opacity: 0.5; margin-right: 6px;"></i>Technician List</a>
                        <a class="nav-link" href="pp-op-statistic.php"><i class="fas fa-chart-bar" style="opacity: 0.5; margin-right: 6px;"></i>Operator Statistic</a>
                        <a class="nav-link" href="pp-staff-add.php"><i class="fas fa-plus" style="opacity: 0.5; margin-right: 6px;"></i>Add Staff</a>
                        <a class="nav-link" href="pp-staff-upload.php"><i class="fas fa-file-import" style="opacity: 0.5; margin-right: 6px;"></i>Import Excel</a>
                    </nav>
                </div>

                <?php if ($id_role_group == 3 && $role_group_name == 'Admin') : ?>
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseAccount" aria-expanded="false" aria-controls="collapseAccount">
                        <div class="nav-link-icon"><i class="fas fa-user"></i></div>
                        Account
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseAccount" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                            <a class="nav-link" href="pp-account-user.php"><i class="fas fa-list" style="opacity: 0.5; margin-right: 6px;"></i>User Account List</a>
                            <a class="nav-link" href="pp-account.php"> <i class="fas fa-edit" style="opacity: 0.5; margin-right: 6px;"></i>  Edit Account  </a>

                        </nav>
                    </div>
                <?php endif; ?>

                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseJobs" aria-expanded="false" aria-controls="collapseJobs">
                    <div class="nav-link-icon"><i class="fas fa-tasks"></i></div>
                    Jobs
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseJobs" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <a class="nav-link" href="pp-job-add.php"><i class="fas fa-plus" style="opacity: 0.5; margin-right: 6px;"></i>Add New Jobs</a>
                        <a class="nav-link" href="pp-upload.php"><i class="fas fa-upload" style="opacity: 0.5; margin-right: 6px;"></i>Upload Jobs</a>
                        <a class="nav-link" href="pp-export.php"><i class="fas fa-file-export" style="opacity: 0.5; margin-right: 6px;"></i>Export Jobs</a>
                    </nav>
                </div>
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseDowntime" aria-expanded="false" aria-controls="collapseDowntime">
                    <div class="nav-link-icon"><i class="fas fa-cogs"></i></div>
                    Setting
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseDowntime" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <a class="nav-link" href="pp-setting-dt.php"><i class="fas fa-list" style="opacity: 0.5; margin-right: 6px;"></i>Downtime List</a>
                        <a class="nav-link" href="pp-setting-dt-add.php"><i class="fas fa-plus" style="opacity: 0.5; margin-right: 6px;"></i>Add Downtime</a>
                    </nav>
                </div>
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseQrcode" aria-expanded="false" aria-controls="collapseQrcode">
                    <div class="nav-link-icon"><i class="fas fa-qrcode"></i></div>
                    QR code
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseQrcode" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <a class="nav-link" href="pp-setting-qrlist.php"><i class="fas fa-list" style="opacity: 0.5; margin-right: 6px;"></i>QR code List</a>
                        <a class="nav-link" href="pp-setting-qr.php"><i class="fas fa-plus" style="opacity: 0.5; margin-right: 6px;"></i>Add QR code</a>
                    </nav>
                </div>



                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseHistory" aria-expanded="false" aria-controls="collapseHistory">
                    <div class="nav-link-icon"><i class="fas fa-history"></i></div>
                    History
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseHistory" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <a class="nav-link menu-link" href="pp-history.php"><i class="fas fa-history" style="opacity: 0.5; margin-right: 6px;"></i>  History List</a>
                    </nav>
                </div>
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseTray" aria-expanded="false" aria-controls="collapseTray">
                    <div class="nav-link-icon"><i class="fas fa-utensils"></i></div> <!-- เปลี่ยนไอคอนเป็น utensils -->
                    Special Tray
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseTray" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <a class="nav-link" href="pp-special-tray.php">Special Tray List</a>
                    </nav>
                </div>

            </div>
        </div>
        <!-- Sidenav Footer-->
        <div class="sidenav-footer">
            <div class="sidenav-footer-content">
                <div class="sidenav-footer-subtitle">Logged in as Role Group: <?php echo $role_group ?></div>
                <div class="sidenav-footer-title">Role: <?php echo $role ?></div>
            </div>
        </div>
    </nav>
</div>

