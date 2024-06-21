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
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
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
    <style>
        @media print {
            /* CSS สำหรับการพิมพ์ */
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
                grid-template-columns: repeat(6, 1fr); /* กำหนดจำนวนคอลัมน์ในกริด */
                gap: 10px;
                padding: 20px;
            }
            .qrcode-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                page-break-inside: avoid;
                break-inside: avoid;
            }
            .qr-code-value {
                font-family: 'Arial', sans-serif;
                font-size: 12px;
                color: #333;
                text-align: center;
                margin-top: 10px;
            }
        }

        .qrcode-container {
            /* CSS สำหรับกล่องที่บรรจุ QR Code */
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 35px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 100%;
            max-width: 1000px;
            margin: 20px auto;
        }

        .qrcode-display {
            /* CSS สำหรับแสดง QR Code */
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); /* ใช้ auto-fit เพื่อให้กริดยืดหยุ่น */
            gap: 10px;
            width: calc(100% - 40px); /* ให้ครอบคลุมความกว้างทั้งหมดของ qrcode-container */
            align-items: center;
            padding: 25px;
            margin: 20px 10px;
        }

        h1 {
            /* CSS สำหรับหัวเรื่อง */
            color: #333;
            font-size: 36px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px; /* เพิ่ม margin ของหัวเรื่องเพื่อระยะห่างที่ดีกว่า */
        }

        #quantity {
            /* CSS สำหรับช่องป้อนจำนวน */
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
            /* CSS สำหรับคอนเทนเนอร์ของ QR Code */
            margin-top: 20px;
            padding: 10px;
        }

        #qrValue {
            /* CSS สำหรับแสดงค่าของ QR Code */
            align-content: center;
            margin-top: 20px;
            font-size: 5px;
            color: #666;
        }

        .button-row {
            width: 100%;
            display: flex;
            justify-content: space-between; /* ทำให้ปุ่มใน .button-row แสดงในแนวนอน และทำให้เว้นวรรคเท่าที่กำหนดได้ */
            align-items: center;
            margin-top: 10px;
        }

        .btnn {
            width: 120px; /* กำหนดขนาดปุ่ม */
            height: 35px;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            text-align: center;
        }

        .btn-gen {
            background-color: #03b384; /* สีพื้นหลังปุ่ม Generate */
        }

        .btn-download {
            background-color: #239e94;
            display: none; /* ปุ่ม Download ซ่อนไว้เริ่มต้น */
        }

        .btn-print {
            background-color: #0c89cd;
            display: none; /* ปุ่ม Print ซ่อนไว้เริ่มต้น */
        }

        .btn-save {
            background-color: #3131b1;
            text-align: center;
        }

    </style>

</head>
<body class="nav-fixed">
<?php require 'pp-setting-sidenavAccordion.php'; ?>
<div id="layoutSidenav">
    <?php require 'pp-layoutSidenav_nav.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-xl justify-content-center align-items-center px-2">
                <h1>Generate QR Code</h1>
                <div id="qrcode-container" class="qrcode-container">
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
                    <label for="quantity">จำนวนที่ต้องการ</label>
                    <input type="number" id="quantity" name="quantity" min="1" max="50" value="1" class="form-control">
                    <button onclick="generateQRCode()" class="btnn btn-gen"> <i class="fas fa-qrcode"></i> Generate</button>
                    <div id="qrcode" class="qrcode-display"></div>
                    <div id="qrValue"></div>
                    <div class="button-row">
                        <button id="downloadBtn" onclick="downloadQRCode()" class="btnn btn-download"> <i class="fas fa-download"></i> Download</button>
                        <button id="printBtn" onclick="printQRCode()" class="btnn btn-print"> <i class="fas fa-print"></i> Print</button>
                        <button id="saveBtn" onclick="saveQRCode()" class="btnn btn-save"> <i class="fas fa-save"></i> Save</button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
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
