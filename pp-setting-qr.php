<?php
require 'pp-session-start.php'
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
    <link rel="stylesheet" href="css/majorette.css">
    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/jquery/jquery-ui.min.js"></script>

    <!--  Generate QR code   -->
    <script type="text/javascript" src="js/majorette/pp-generate-qr.js"></script>


    <style>
        #quantity {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        #qrcodeContainer {
            display: flex;
            justify-content: center;
            align-items: stretch;
            flex-direction: column;
            padding: 40px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 80%;
            max-width: 400px;
        }

        .qrcode-item {
            display: inline-block;
            margin: 10px 0;
        }


        h1 {
            color: #333;
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 10px;
            margin-top: 30px;
            text-align: center;
        }
        #downloadBtn{
            margin-top: 20px;
        }
        #printBtn{
            margin-top: 20px;
        }
        #saveBtn{
            margin-top: 20px;
        }

        #qrcode {
            margin-top: 20px;
        }

        #qrValue {
            margin-top: 20px;
            /*padding: 10px;*/
            font-size: 18px;
            color: #666;
        }
    </style>
</head>
<body class="nav-fixed">
<?php require 'pp-setting-sidenavAccordion.php'; ?>
<div id="layoutSidenav">
    <?php require 'pp-layoutSidenav_nav.php'; ?>
    <div id="layoutSidenav_content">
        <h1>Generate QR Code</h1>
        <div id="qrcodeContainer" class="container">
            <label for="quantity">จำนวนที่ต้องการสร้าง:</label>
            <input type="number" id="quantity" name="quantity" min="1" max="100" value="1" class="form-control">
            <button onclick="generateQRCode()" class="btn btn-primary">Generate QR Code</button>
            <div id="qrcode"></div>
            <div id="qrValue"></div>
            <button id="downloadBtn" onclick="downloadQRCode()" class="btn btn-success btn-block">Download QR Code</button>
            <button id="printBtn" onclick="openPrintDialog()" class="btn btn-info btn-block">Print QR Code</button>
            <button id="saveBtn" onclick="saveQRCode()" class="btn btn-primary btn-block">Save QR Code</button>

        </div>

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
</body>
</html>
