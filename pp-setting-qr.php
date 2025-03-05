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
    <title>QR CODE For Task</title>
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
    <script type="text/javascript" src="js/majorette/pp-generate-qr.js"></script>
    <script type="text/javascript" src="js/majorette/pp-setting-dt.js"></script>
    <script type="text/javascript" src="js/majorette/pp-machine-refresh-3.js"></script>
    <script type="text/javascript" src="js/majorette/pp-machine-clock.js"></script>
    <!-- PDF-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

    <!-- css-->
    <link rel="stylesheet" href="css/pp-sidenav.css">

    <!-- qrcode-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>


    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #printableArea, #printableArea * {
                visibility: visible;
            }

            #printableArea {
                position: absolute;
                left: 0;
                top: 0;
                display: grid;
                grid-template-columns: repeat(5, 1fr);
                gap: 20px;
                padding: 20px;
            }

            .qrcode-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 10px;
                border: 2px solid #000;
                border-radius: 8px;
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .qr-code-value {
                font-family: 'Arial', sans-serif;
                font-size: 14px;
                color: #333;
                text-align: center;
                margin-top: 10px;
            }
        }

        h1 {
            color: #333;
            font-size: 36px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        #quantity {
            width: 100%;
            max-width: 120px;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            text-align: center;
        }

        #qrcode {
            margin-top: 20px;
            padding: 10px;
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
            color: white !important;
        }

        .btn-save {
            background-color: #2c2c88;
            color: white;
        }

        .btn-save:hover {
            background-color: #444488;
            color: white;
        }

        .btn-gen {
            background-color: #03b384;
            color: white;
        }

        .btn-download {
            background-color: #239e94;
            color: white;
            display: none;
        }

        .btn-print {
            background-color: #0c89cd;
            color: white;
            display: none;
        }

        .card-header {
            background-color: red;
            color: white !important;
            font-size: 24px;
            font-weight: bold;
        }


        .table thead {
            background-color: #ffea07;
            color: #000;
        }
        /* เอฟเฟกต์ปุ่ม */
        .btn {
            transition: all 0.2s ease-in-out;
        }

        /* เมื่อเมาส์ไปชี้ที่ปุ่ม */
        .btn:hover {
            transform: scale(1.1); /* ขยายปุ่มเล็กน้อย */
            opacity: 0.9;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2); /* เพิ่มเงา */
        }

        /* เอฟเฟกต์ตอนกดปุ่ม */
        .btn:active {
            transform: scale(0.95); /* หดปุ่มเล็กน้อย */
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.15); /* ลดเงาลง */
        }

        /* ปรับสีปุ่มเฉพาะ */
        .btn-gen {
            background-color: #03b384;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
        }
        .btn-download{
            background-color: #239e94;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
        }
        .btn-print {
            background-color: #0c89cd;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;

        }
        .btn-print:hover {
            background-color: #0c7bb5;
        }

        .btn-print:active {
            background-color: #0b75ac;
        }
        .btn-download:hover {
            background-color: #21958d;
        }

        .btn-download:active {
            background-color: #1f8c84;
        }
        .btn-gen:hover {
            background-color: #029973;
        }

        .btn-gen:active {
            background-color: #017a57;
        }

        .btn-save {
            background-color: #2c2c88;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
        }

        .btn-save:hover {
            background-color: #444488;
        }

        .btn-save:active {
            background-color: #232366;
        }


    </style>
</head>
<body class="nav-fixed">
<?php require 'pp-setting-sidenavAccordion.php'; ?>
<div id="layoutSidenav">
    <?php require 'pp-layoutSidenav_nav.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <div class="card">
                    <div class="card-header text-center">Generate QR Code</div>
                    <div class="card-body">
                        <div class="mb-3 text-center">
                            <label for="quantity" class="form-label fw-bold">จำนวนที่ต้องการ</label>
                            <input type="number" id="quantity" name="quantity" min="1" max="50" value="1" class="form-control w-25 mx-auto">
                            <button onclick="generateQRCode()" class="btn btn-gen mt-3">
                                <i class="fas fa-qrcode"></i>Generate
                            </button>
                        </div>
                        <div id="qrcode" class="d-flex flex-wrap justify-content-center gap-3"></div>
                        <div class="text-center mt-4">
                            <button id="saveBtn" onclick="saveQRCode()" class="btn btn-save">
                                <i class="fas fa-save"></i> Save
                            </button>
                            <button id="downloadBtn" onclick="downloadQRCode()" class="btn btn-download">
                                <i class="fas fa-download"></i> Download
                            </button>
                            <button id="printBtn" onclick="printQRCode()" class="btn btn-print">
                                <i class="fas fa-print"></i> Print
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="js/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
<script src="js/simple-datatables@latest" type="text/javascript"></script>
<script src="js/datatables/datatables-simple-demo.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="js/litepicker/dist/bundle.js"></script>
<script src="js/litepicker.js"></script>
<script type="text/javascript" src="js/majorette/pp-session.js"></script>
<script>
    function showDownloadPrintButtons() {
        document.getElementById('downloadBtn').style.display = 'inline-block';
        document.getElementById('printBtn').style.display = 'inline-block';
    }
</script>
</body>
</html>
