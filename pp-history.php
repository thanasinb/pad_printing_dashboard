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
    <title>History List</title>
    <link href="css/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/litepicker/dist/css/litepicker.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/file.png" />
    <script data-search-pseudo-elements defer src="js/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="js/feather-icons/4.28.0/feather.min.js"></script>
    <link rel="stylesheet" href="css/reorder-columns/dragtable.css">
    <link rel="stylesheet" href="css/reorder-columns/bootstrap-table.min.css">
    <link rel="stylesheet" href="css/majorette.css">
    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/jquery/jquery-ui.min.js"></script>

    <script type="text/javascript" src="js/majorette/pp-session.js"></script>
    <link rel="stylesheet" href="css/pp-sidenav.css">

    <!-- filter css--->
    <link rel="stylesheet" href="css/pp-history-filter.css">

    <!--    <link rel="stylesheet" href="css/history-table.css">-->

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



    /* เอฟเฟกต์ปุ่ม Close (สีม่วง) */
    .form-button-add {
        transition: all 0.2s ease-in-out;
        background-color: #28a745;
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
    .form-button-add:hover {
        transform: scale(1.1);
        opacity: 0.9;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        border-color: #186027;
    }

    /* เอฟเฟกต์ตอนกดปุ่ม Close */
    .form-button-add:active {
        transform: scale(0.95);
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.15);
        background-color: #176328;
    }
    /* ปุ่มใน Dark Mode */
    body.dark-mode .form-button-add {
        background-color: #176328 ; /* สีม่วงหม่น */
        border-color: #176328 ; /* สีเส้นขอบ */
        color: white ; /* สีตัวอักษร */
    }

    /* Hover ใน Dark Mode */
    body.dark-mode .form-button-add:hover {
        background-color: #11491c; /* สีม่วงหม่น */
    }












</style>
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
                <!-- Example DataTable for Dashboard Demo-->
                <div class="card mb-4 w-100" id="table-machine">
                    <div class="card-header bg-red fw-bold text-white fs-4 d-flex justify-content-between">
                        <div>History List </div>
                        <!--                        <div>-->
                        <!--                            <span id="hours"></span> :-->
                        <!--                            <span id="minutes"></span> :-->
                        <!--                            <span id="seconds"></span>-->
                        <!--                        </div>-->
                    </div>

                    <div class="modal fade" id="historyTableModal" tabindex="-1" aria-labelledby="historyTableModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="historyTableModalLabel">History Table</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-striped table-bordered text-center">
                                        <thead>
                                        <tr>
                                            <th><i class="me-2 text-green" data-feather="list"></i>List</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td><i class="me-2 text-green" data-feather="log-in"></i> Login</td>
                                        </tr>
                                        <tr>
                                            <td><i class="me-2 text-red" data-feather="log-out"></i>Logout (Log out yourself)</td>
                                        </tr>
                                        <tr>
                                            <td><i class="me-2 text-gray" data-feather="log-out"></i>Logout (session expired)</td>
                                        </tr>
                                        <tr>
                                            <td><i class="me-2 text-blue" data-feather="download"></i> Download QR Code</td>
                                        </tr>
                                        <tr>
                                            <td><i class="me-2 text-blue" data-feather="user-plus"></i> เพิ่มข้อมูลพนักงาน</td>
                                        </tr>
                                        <tr>
                                            <td><i class="me-2 text-blue" data-feather="edit"></i> แก้ไขข้อมูลของพนักงาน</td>
                                        </tr>
                                        <tr>
                                            <td><i class="me-2 text-blue" data-feather="minus-circle"></i> ลบช้อมูลพนักงาน</td>
                                        </tr>
                                        <tr>
                                            <td><i class="me-2 text-blue" data-feather="user-check"></i> แก้ไข Username ของบัญชีพนักงาน</td>
                                        </tr>
                                        <tr>
                                            <td><i class="me-2 text-blue" data-feather="user-check"></i> แก้ไข Password ของบัญชีพนักงาน</td>
                                        </tr>
                                        <tr>
                                            <td><i class="me-2 text-blue" data-feather="save"></i> บันทึก QR CODE</td>
                                        </tr>
                                        <tr>
                                            <td><i class="me-2 text-blue" data-feather="edit"></i> แก้ไข Description ใน Downtime</td>
                                        </tr>
                                        <tr>
                                            <td><i class="me-2 text-blue" data-feather="edit"></i> แก้ไข Downtime Code</td>
                                        </tr>
                                        <tr>
                                            <td><i class="me-2 text-green" data-feather="plus-circle"></i> เพิ่ม Downtime</td>
                                        </tr>
                                        <tr>
                                            <td><i class="me-2 text-red" data-feather="minus-circle"></i> ลบ Downtime</td>
                                        </tr>

                                        <tr>
                                            <td><i class="me-2 text-green" data-feather="plus-circle"></i> เพิ่มผู้ใช้</td>
                                        </tr>
                                        <tr>
                                            <td><i class="me-2 text-red" data-feather="minus-circle"></i> ลบผู้ใช้</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="historyModalLabel">รายละเอียดการแก้ไขข้อมูลพนักงาน</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>ชื่อพนักงานที่แก้ไข:</strong> <span id="modal-name"></span></p>
                                    <p><strong id="modal-detail-title">รายละเอียดการแก้ไข:</strong></p>
                                    <ul id="modal-details" class="list-group"></ul>

                                    <!-- 🔹 ส่วนที่เพิ่มเข้ามาเพื่อแสดงภาพและรหัส QR Code -->
                                    <div id="qr-code-container" style="display: none; text-align: center; margin-top: 20px;">
                                        <p><strong>💾 QR Code ที่บันทึก:</strong> <span id="qr-code-text" style="color: red;"></span></p>
                                        <img id="qr-code-image" src="" alt="QR Code" style="max-width: 200px; display: block; margin: auto;">
                                    </div>
                                    <!-- 🔹 ส่วนที่เพิ่มเข้ามาเพื่อแสดง QR Code พร้อมข้อความด้านล่าง -->
                                    <!--                                    <div id="qr-code-container" style="display: none; text-align: center; margin-top: 20px;">-->
                                    <!--                                        <div style="border: 2px solid black; padding: 10px; display: inline-block; border-radius: 10px;">-->
                                    <!--                                            <img id="qr-code-image" src="" alt="QR Code" style="max-width: 200px; display: block; margin: auto;">-->
                                    <!--                                            <p id="qr-code-text" style="margin-top: 5px; font-size: 16px; font-weight: bold;">Code: </p>-->
                                    <!--                                        </div>-->
                                    <!--                                    </div>-->

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn form-button-reset" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const historyModal = document.getElementById('historyModal');
                            const modalTitle = document.getElementById('historyModalLabel');
                            const modalDetailTitle = document.getElementById('modal-detail-title');
                            const qrCodeContainer = document.getElementById('qr-code-container');
                            const qrCodeImage = document.getElementById('qr-code-image');
                            const qrCodeText = document.getElementById('qr-code-text');

                            historyModal.addEventListener('show.bs.modal', function (event) {
                                const button = event.relatedTarget;
                                const name = button.getAttribute('data-name') || "N/A";
                                const action = button.getAttribute('data-action') || "";
                                let details = button.getAttribute('data-details') || "[]";

                                console.log("Action ที่ได้รับ:", action);  // ✅ ตรวจสอบค่าที่ได้รับใน Console

                                document.getElementById('modal-name').textContent = name;
                                const detailsList = document.getElementById('modal-details');
                                detailsList.innerHTML = '';

                                // 🔹 กำหนดหัวข้อ Modal ตามประเภทของ Action
                                if (action.startsWith('สร้างพนักงาน')) {
                                    modalTitle.textContent = 'การสร้างพนักงาน';
                                    modalDetailTitle.innerHTML = `รายละเอียด: <span style="color: orangered;">${action}</span>`;
                                } else if (action.startsWith('บันทึก QR Code')) {
                                    modalTitle.textContent = 'การบันทึก QR Code';
                                    modalDetailTitle.innerHTML = `รายละเอียด: <span style="color: orangered;">${action}</span>`;
                                } else if (action.startsWith('สร้าง job ด้วย Job ID:')) {
                                    modalTitle.textContent = 'การเพิ่มงานให้ Machine';
                                    modalDetailTitle.innerHTML = `รายละเอียด: <span style="color: orangered;">${action}</span>`;
                                } else if (action.startsWith('แก้ไขบัญชีของ:')) {
                                    modalTitle.textContent = 'การแก้ไขบัญชีผู้ใช้';
                                    modalDetailTitle.innerHTML = `รายละเอียด: <span style="color: orangered;">${action}</span>`;
                                }else if (action.startsWith('แก้ไข job ID')) {
                                    modalTitle.textContent = 'การเปลี่ยนงานให้ Machine';
                                    modalDetailTitle.innerHTML = `รายละเอียด: <span style="color: orangered;">${action}</span>`;
                                } else if (action.startsWith('เพิ่ม Downtime Code')) {
                                    modalTitle.textContent = 'การเพิ่ม Downtime';
                                    modalDetailTitle.innerHTML = `รายละเอียด: <span style="color: orangered;">${action}</span>`;
                                } else if (action.startsWith('ลบพนักงาน:')) {
                                    modalTitle.textContent = 'การลบพนักงาน';
                                    modalDetailTitle.innerHTML = `รายละเอียด: <span style="color: orangered;">${action}</span>`;
                                }  else if (action.startsWith('ลบผู้ใช้:')) {
                                    modalTitle.textContent = 'การลบผู้ใช้';
                                    modalDetailTitle.innerHTML = `รายละเอียด: <span style="color: orangered;">${action}</span>`;
                                }else if (action.startsWith('สร้าง Machine ID:')) {
                                    modalTitle.textContent = 'การสร้าง Machine';
                                    modalDetailTitle.innerHTML = `รายละเอียด: <span style="color: orangered;">${action}</span>`;
                                }else if (action.startsWith('แก้ไขข้อมูล Machine ID:')) {
                                    modalTitle.textContent = 'การแก้ไข Machine';
                                    modalDetailTitle.innerHTML = `รายละเอียด: <span style="color: orangered;">${action}</span>`;
                                }else if (action.startsWith('ลบ Machine ที่มี ID')) {
                                    modalTitle.textContent = 'การลบ Machine';
                                    modalDetailTitle.innerHTML = `รายละเอียด: <span style="color: orangered;">${action}</span>`;
                                }else if (action.startsWith('แก้ไข Downtime Box Code:')) {
                                    modalTitle.textContent = 'การแก้ไข Downtime Code';
                                    modalDetailTitle.innerHTML = `รายละเอียด: <span style="color: orangered;">${action}</span>`;
                                }else if (action.startsWith(' เพิ่มผู้ใช้:')) {
                                    modalTitle.textContent = 'การเพิ่มผู้ใช้:';
                                    modalDetailTitle.innerHTML = `รายละเอียด: <span style="color: orangered;">${action}</span>`;
                                }else if (action.startsWith('ลบ downtime:')) {
                                    modalTitle.textContent = 'การลบ downtime';
                                    modalDetailTitle.innerHTML = `รายละเอียด: <span style="color: orangered;">${action}</span>`;
                                }else if (action.startsWith('แก้ไขข้อมูล Job ID:')) {
                                    modalTitle.textContent = 'การแก้ไขข้อมูล Job ';
                                    modalDetailTitle.innerHTML = `รายละเอียด: <span style="color: orangered;">${action}</span>`;
                                }else if (action.startsWith('เอา job ID')) {
                                    modalTitle.textContent = 'การลบ job';
                                    modalDetailTitle.innerHTML = `รายละเอียด: <span style="color: orangered;">${action}</span>`;
                                }else {
                                    modalTitle.textContent = 'การแก้ไขข้อมูลพนักงาน';
                                    modalDetailTitle.innerHTML = `รายละเอียด: <span style="color: orangered;">${action}</span>`;
                                }

                                // 🔹 แปลง JSON details และป้องกัน Error
                                try {
                                    details = JSON.parse(details);
                                    if (Array.isArray(details) && details.length > 0) {
                                        details.forEach(change => {
                                            let formattedText = change.includes("→")
                                                ? `<strong>✏️ ${change.split(" → ")[0]}</strong> <span style="color: black;">→</span> <span style="color: red;">${change.split(" → ")[1]}</span>`
                                                : `<strong>➕ ${change}</strong>`;

                                            const listItem = document.createElement('li');
                                            listItem.className = 'list-group-item';
                                            listItem.innerHTML = formattedText;
                                            detailsList.appendChild(listItem);
                                        });
                                    }

                                    // ✅ แสดง QR Code ถ้ามีข้อมูล
                                    if (details.qr_code && details.qr_code_image) {
                                        qrCodeContainer.style.display = 'block';
                                        qrCodeText.textContent = details.qr_code;
                                        qrCodeImage.src = details.qr_code_image;
                                    } else {
                                        qrCodeContainer.style.display = 'none';
                                    }

                                    //

                                } catch (error) {
                                    console.error("Error parsing JSON:", error);
                                    detailsList.innerHTML = '<li class="list-group-item text-danger">เกิดข้อผิดพลาดในการแสดงข้อมูล</li>';
                                    qrCodeContainer.style.display = 'none';
                                }
                            });
                        });
                    </script>

                    <div class="body">
                        <form id="combinedFilterForm" method="GET" class="form-container">


                            <!-- 🔴 บล็อกบน (row1): จัดเป็น Flexbox เองในสไตล์ inline เพื่อให้อยู่แถวเดียว -->
                            <div style="
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        gap: 15px;
    ">
                                <!-- Start Date -->
                                <div class="form-group">
                                    <label for="start_date" class="form-label">Start Date:</label>
                                    <input type="date" id="start_date" name="start_date"
                                           value="<?php echo $_GET['start_date'] ?? ''; ?>"
                                           class="form-input">
                                </div>

                                <!-- End Date -->
                                <div class="form-group">
                                    <label for="end_date" class="form-label">End Date:</label>
                                    <input type="date" id="end_date" name="end_date"
                                           value="<?php echo $_GET['end_date'] ?? ''; ?>"
                                           class="form-input">
                                </div>

                                <!-- ปุ่มต่าง ๆ -->
                                <div class="form-actions">
                                    <button type="submit" class="form-button form-button-submit">Filter</button>
                                    <button type="button" id="resetFilters" class="form-button form-button-reset">Reset</button>
                                    <button type="button" id="addFilter" class="form-button form-button-add">+</button>
                                    <button type="button" id="removeFilter" class="form-button form-button-reset">-</button>
                                </div>
                            </div>
                            <!-- /บล็อกบน (row1) -->

                            <!-- 🔵 บล็อกล่าง (row2): ส่วน Action หลัก + Extra Filters -->
                            <div style="
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: flex-end;
    ">

                                <!-- Action หลัก -->
                                <div class="form-group">
                                    <label for="filter-action" class="form-label">Action:</label>
                                    <select id="filter-action" name="filter_action[]" class="form-select">
                                        <option value="">All Actions</option>
                                        <option value="Login" data-icon="log-in">🔐 Login</option>
                                        <option value="Logout (Log out yourself)" data-icon="log-out">🔓 Logout (Log out yourself)</option>
                                        <option value="Logout (session expired)" data-icon="log-out">⌛ Logout (session expired)</option>
                                        <option value="Download QR Code" data-icon="download">⬇️ ดาวน์โหลด QR Code ลงเครื่อง</option>
                                        <option value="บันทึก QR Code" data-icon="save">💾 บันทึก QR Code ลงฐานข้อมูล</option>
                                        <option value="สร้างพนักงาน">👤 สร้างพนักงาน</option>
                                        <option value="แก้ไขข้อมูลพนักงาน">✏️ แก้ไขพนักงาน</option>
                                        <option value="ลบพนักงาน:">➖ ลบพนักงาน</option>
                                        <option value="เพิ่มผู้ใช้" data-icon="plus-circle">➕ เพิ่มบัญชีผู้ใช้ </option>
                                        <option value="แก้ไขบัญชีของ:">✏️ แก้ไขบัญชีผู้ใช้</option>
                                        <option value="ลบผู้ใช้" data-icon="minus-circle">➖ ลบบัญชีผู้ใช้</option>
                                        <option value="สร้าง Machine ID:">➕ สร้างเครื่อง </option>
                                        <option value="แก้ไขข้อมูล Machine ID:">✏️ แก้ไขเครื่อง</option>
                                        <option value="ลบ Machine ที่มี ID">➖ ลบเครื่อง</option>
                                        <option value="เพิ่ม Downtime Code:" data-icon="plus-circle">➕ เพิ่ม Downtime code</option>
                                        <option value="แก้ไข Downtime Box Code:" data-icon="edit">✏️ แก้ไข Downtime code</option>
                                        <option value="ลบ Downtime" data-icon="minus-circle">➖ ลบ Downtime code</option>
                                        <option value="สร้าง job ด้วย Job ID:" data-icon="plus-circle">➕ สร้างงาน</option>
                                        <option value="แก้ไขข้อมูล Job ID:" data-icon="edit">✏️ แก้ไขข้อมูลงาน</option>
                                        <option value="ลบงาน: Work Order" data-icon="minus-circle">➖ ลบงาน</option>
                                        <option value="เพิ่ม job ID" data-icon="plus-circle">➕ เพิ่มงาน ไปที่ เครื่อง </option>
                                        <option value="แก้ไข job ID" data-icon="edit">✏️ แก้ไข งาน ที่เครื่อง </option>
                                        <option value="เอา job ID" data-icon="minus-circle">➖ ลบงาน ออกจาก เครื่อง</option>
                                    </select>
                                </div>

                                <!-- ช่องว่างสำหรับใส่ action ที่เพิ่ม -->
                                <div id="extra-filters" style="
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        "></div>
                            </div>
                            <!-- /บล็อกล่าง (row2) -->
                        </form>


                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                // ตรวจสอบโหมดมืดและปรับกรอบตาม
                                const isDarkMode = document.body.classList.contains('dark-mode');
                                if (isDarkMode) {
                                    document.querySelector('.form-container').style.borderColor = '#555';
                                } else {
                                    document.querySelector('.form-container').style.borderColor = '#ccc';
                                }
                            });
                        </script>

                        <div style="padding: 1rem;">

                            <table id="datatablesSimple" class="table table-striped" style="width: 100%; white-space: nowrap">
                                <thead class="text-black" style="background-color: #ffea07">

                                <?php require_once 'pp-history-table-head.php'; ?>

                                </thead>
                                <tbody id="table_body">

                                <?php require 'pp-history-script.php'; ?>
                                </tbody>

                            </table>
                        </div>

                    </div>

                    <!--                    <button id="showHistoryTableBtn" class="btn btn-primary btn-apple-style">Show History Table</button>-->

                </div>
            </div>
        </main>
    </div>
</div>

<script src="js/majorette/pp-history-filter.js"></script>


<!--<script src="js/majorette/pp-time-stamp.js"></script>-->
<script src="js/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
<script src="js/simple-datatables@latest" type="text/javascript"></script>
<script src="js/datatables/datatables-staff.js"></script>
<script src="js/litepicker/dist/bundle.js"></script>
<script src="js/litepicker.js"></script>

</body>
</html>