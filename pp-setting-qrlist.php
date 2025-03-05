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
    <title>User List</title>
    <link href="css/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/litepicker/dist/css/litepicker.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/qr-code.png" />
    <script data-search-pseudo-elements defer src="js/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="js/feather-icons/4.28.0/feather.min.js"></script>
    <link rel="stylesheet" href="css/reorder-columns/dragtable.css">
    <link rel="stylesheet" href="css/reorder-columns/bootstrap-table.min.css">
    <link rel="stylesheet" href="css/majorette.css">
    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/jquery/jquery-ui.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<style>


    .btn {
        transition: all 0.3s ease;
    }

    .btn:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transform: translateY(-2px);
        color: white !important;
    }

    .qr-thumbnail {
        transition: all 0.2s ease-in-out;
    }

    /* เมื่อเมาส์ไปชี้ที่ QR Code */
    .qr-thumbnail:hover {
        transform: scale(1.1); /* ขยายขึ้นเล็กน้อย */
        opacity: 0.9;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2); /* เพิ่มเงา */
    }

    /* เอฟเฟกต์ตอนกด QR Code */
    .qr-thumbnail:active {
        transform: scale(0.95); /* หดลงเล็กน้อย */
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.15); /* ลดเงา */
    }


    /* เอฟเฟกต์ปุ่ม Close (สีม่วง) */
    .btn-close-modal {
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
    .btn-close-modal:hover {
        transform: scale(1.1);
        opacity: 0.9;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        background-color: #d32f2f;
    }

    /* เอฟเฟกต์ตอนกดปุ่ม Close */
    .btn-close-modal:active {
        transform: scale(0.95);
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.15);
        background-color: #a52020;
    }
    /* ปุ่มใน Dark Mode */
    body.dark-mode .btn-close-modal {
        background-color:#A52A2A !important; /* สีม่วงหม่น */
        border-color: #A52A2A !important; /* สีเส้นขอบ */
        color: white !important; /* สีตัวอักษร */
    }

    /* Hover ใน Dark Mode */
    body.dark-mode .btn-close-modal:hover {
        background-color: #8c2424 !important; /* สีม่วงหม่น */
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
<!--                        <div>-->
<!--                            <span id="hours"></span> :-->
<!--                            <span id="minutes"></span> :-->
<!--                            <span id="seconds"></span>-->
<!--                        </div>-->
                    </div>

                    <div class="card-body">
                        <table id="datatablesSimple" class="table table-striped" style="width: 100%; white-space: nowrap">
                            <thead class="text-black" style="background-color: #ffea07">
                            <?php require'pp-setting-qr-table-head.php' ?>
                            </thead>
                            <tbody id="table_body">
                            <?php
                            require 'pp-setting-qr-script.php';
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <script>
                        function showQrPopup(qrSrc, qrText, event) {
                            // ดึงตำแหน่งของรูป QR Code ที่กด
                            const rect = event.target.getBoundingClientRect();
                            const centerX = rect.left + rect.width / 2;
                            const centerY = rect.top + rect.height / 2;

                            // สร้าง div สำหรับพื้นหลัง Popup
                            let qrPopup = document.createElement("div");
                            qrPopup.id = "qrPopup";
                            qrPopup.style.position = "fixed";
                            qrPopup.style.top = "0";
                            qrPopup.style.left = "0";
                            qrPopup.style.width = "100%";
                            qrPopup.style.height = "100%";
                            qrPopup.style.background = "rgba(0, 0, 0, 0.8)";
                            qrPopup.style.display = "flex";
                            qrPopup.style.justifyContent = "center";
                            qrPopup.style.alignItems = "center";
                            qrPopup.style.zIndex = "1000";
                            qrPopup.style.opacity = "0";
                            qrPopup.style.transition = "opacity 0.3s ease-in-out";

                            // สร้าง div สำหรับ QR Code และข้อความ
                            let qrContainer = document.createElement("div");
                            qrContainer.style.position = "absolute";
                            qrContainer.style.left = `${centerX}px`;
                            qrContainer.style.top = `${centerY}px`;
                            qrContainer.style.width = "auto";
                            qrContainer.style.padding = "20px";
                            qrContainer.style.background = "white";
                            qrContainer.style.borderRadius = "15px";
                            qrContainer.style.textAlign = "center";
                            qrContainer.style.boxShadow = "0px 15px 30px rgba(0,0,0,0.2)";
                            qrContainer.style.transform = "scale(0.2)";
                            qrContainer.style.transition = "transform 0.3s ease-in-out, left 0.3s ease-in-out, top 0.3s ease-in-out";

                            // สร้าง QR Code image (เพิ่มขนาด)
                            let qrImage = document.createElement("img");
                            qrImage.src = qrSrc;
                            qrImage.style.width = "300px";  // ปรับขนาดใหญ่ขึ้น
                            qrImage.style.height = "300px";
                            qrImage.style.border = "5px solid white";
                            qrImage.style.borderRadius = "10px";

                            // สร้างข้อความรหัส QR Code
                            let qrCodeText = document.createElement("p");
                            qrCodeText.textContent = qrText;
                            qrCodeText.style.marginTop = "10px";
                            qrCodeText.style.fontSize = "20px";
                            qrCodeText.style.fontWeight = "bold";
                            qrCodeText.style.color = "black";

                            // ใส่ข้อมูลทั้งหมดลงใน Container
                            qrContainer.appendChild(qrImage);
                            qrContainer.appendChild(qrCodeText);
                            qrPopup.appendChild(qrContainer);
                            document.body.appendChild(qrPopup);

                            // 📌 ใช้ requestAnimationFrame() เพื่อให้แอนิเมชันสมูท
                            requestAnimationFrame(() => {
                                qrPopup.style.opacity = "1";
                                qrContainer.style.transform = "translate(-50%, -50%) scale(1)";
                                qrContainer.style.left = "50%";
                                qrContainer.style.top = "50%";
                            });

                            // 📌 ปิด Popup เมื่อคลิกที่พื้นที่นอก QR Code
                            qrPopup.addEventListener("click", function(event) {
                                if (event.target === qrPopup) {
                                    qrPopup.style.opacity = "0";
                                    qrContainer.style.transform = "translate(-50%, -50%) scale(0.2)";
                                    setTimeout(() => document.body.removeChild(qrPopup), 300);
                                }
                            });

                            // 📌 ปิดด้วยปุ่ม `Esc`
                            document.addEventListener("keydown", function escHandler(event) {
                                if (event.key === "Escape") {
                                    qrPopup.style.opacity = "0";
                                    qrContainer.style.transform = "translate(-50%, -50%) scale(0.2)";
                                    setTimeout(() => {
                                        document.body.removeChild(qrPopup);
                                        document.removeEventListener("keydown", escHandler);
                                    }, 300);
                                }
                            });
                        }
                    </script>
                    <script>
                        $(document).ready(function() {
                            let qrToDelete = '';

                            // เมื่อกดปุ่มลบ QR Code
                            $('.delete_qr').click(function() {
                                qrToDelete = $(this).data('id_qr');
                                $('#qr_code_to_delete').text("QR Code: " + qrToDelete);
                            });

                            // เมื่อกดยืนยันลบ
                            $('#confirm_delete_qr').click(function() {
                                $.ajax({
                                    url: 'pp-setting-qrlist-delete.php',
                                    type: 'POST',
                                    data: { id_qr: qrToDelete },
                                    success: function(response) {
                                        if (response == "success") {
                                            alert("QR Code deleted successfully!");
                                            location.reload(); // รีโหลดหน้าเพื่ออัปเดตตาราง
                                        } else {
                                            alert("Failed to delete QR Code.");
                                        }
                                    }
                                });
                            });
                        });
                    </script>

                    <!-- Modal for deleting QR Code -->
                    <div class="modal fade" id="delete_qr_modal" tabindex="-1" role="dialog" aria-labelledby="delete_qr_modal_label" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="delete_qr_modal_label">Confirm QR Code Deletion</h5>
                                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete this QR Code?
                                    <p class="text-danger fw-bold" id="qr_code_to_delete"></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-close-modal" data-bs-dismiss="modal">Close</button>
                                    <button type="button" id="confirm_delete_qr" class="btn btn-confirm-modal">Delete</button>
                                </div>
                            </div>
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
<script src="js/majorette/pp-time-stamp.js"></script> <!-- อ้างอิงไฟล์ pp-time-stamp.js -->
<script type="text/javascript" src="js/majorette/pp-session.js"></script>


</body>
</html>