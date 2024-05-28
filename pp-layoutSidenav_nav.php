<?php

?>

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
                        <a class="nav-link" href="pp-machine-3.php">Machine List</a>
                        <a class="nav-link" href="pp-machine-statistic.php">Machine Statistic</a>
                        <a class="nav-link" href="pp-machine-add.php">Add new Machine</a>
                    </nav>
                </div>
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseStaffs" aria-expanded="false" aria-controls="collapseStaffs">
                    <div class="nav-link-icon"><i class="fas fa-users"></i></div>
                    Staffs
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseStaffs" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <a class="nav-link" href="pp-staff-operator.php">Operator List</a>
                        <a class="nav-link" href="pp-staff-technician.php">Technician List</a>
                        <a class="nav-link" href="pp-op-statistic.php">Operator Statistic</a>
                        <a class="nav-link" href="pp-staff-add.php">Add Staff</a>
                        <a class="nav-link" href="pp-staff-upload.php">Import Excel</a>
                    </nav>
                </div>
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseJobs" aria-expanded="false" aria-controls="collapseJobs">
                    <div class="nav-link-icon"><i class="fas fa-tasks"></i></div>
                    Jobs
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseJobs" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <a class="nav-link" href="pp-job-add.php">Add New Jobs</a>
                        <a class="nav-link" href="pp-upload.php">Upload Jobs</a>
                        <a class="nav-link" href="pp-export.php">Export Jobs</a>
                    </nav>
                </div>
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseDowntime" aria-expanded="false" aria-controls="collapseDowntime">
                    <div class="nav-link-icon"><i class="fas fa-cogs"></i></div>
                    Setting
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseDowntime" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <a class="nav-link" href="pp-setting-dt.php">Downtime List</a>
                        <a class="nav-link" href="pp-setting-dt-add.php">Add Downtime</a>
                    </nav>
                </div>
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseQrcode" aria-expanded="false" aria-controls="collapseQrcode">
                    <div class="nav-link-icon"><i class="fas fa-qrcode"></i></div>
                    QR code
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseQrcode" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <a class="nav-link" href="pp-setting-qr.php">Add QR code</a>
                        <a class="nav-link" href="pp-setting-qrlist.php">QR code List</a>
                    </nav>
                </div>

                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseAccount" aria-expanded="false" aria-controls="collapseAccount">
                    <div class="nav-link-icon"><i class="fas fa-user"></i></div>
                    Account
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseAccount" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <a class="nav-link" href="pp-account.php">Edit Account</a>
                        <a class="nav-link" href="pp-account-user.php">Edit User Account</a>

                    </nav>
                </div>
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseHistory" aria-expanded="false" aria-controls="collapseHistory">
                    <div class="nav-link-icon"><i class="fas fa-history"></i></div>
                    History
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseHistory" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <a class="nav-link" href="pp-history.php">History List</a>
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
                <div class="sidenav-footer-subtitle">Logged in as:   <?php echo $role_group ?></div>
                <div class="sidenav-footer-title"><?php echo $role ?></div>
            </div>
        </div>
    </nav>
</div>

