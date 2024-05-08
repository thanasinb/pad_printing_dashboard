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
    <script type="text/javascript" src="js/majorette/pp-setting-dt.js"></script>
    <script type="text/javascript" src="js/majorette/pp-machine-refresh-3.js"></script>
    <script type="text/javascript" src="js/majorette/pp-machine-clock.js"></script>

    <style>
     

        #qrcodeContainer {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin-top: 40px;
            padding: 40px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 80%;
            max-width: 400px;
        }

        h1 {
            color: #333;
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 30px;
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

        <main>
        <div id="qrcodeContainer" class="container">
            <h1>Generate QR Code</h1>
            <button onclick="generateQRCode()" class="btn btn-primary">Generate QR Code</button>
            <div id="qrcode"></div>
            <div id="qrValue"></div>
            <button id="downloadBtn" onclick="downloadQRCode()" class="btn btn-success btn-block">Download QR Code</button>

            <button id="printBtn" onclick="openPrintDialog()" class="btn btn-info btn-block">Print QR Code</button>

            <button id="saveBtn" onclick="saveQRCode()" class="btn btn-primary btn-block">Save QR Code</button>

            <script>function saveQRCode() {
                    const qrCodeValueContainer = document.getElementById('qrValue');
                    const qrCodeValue = qrCodeValueContainer.textContent.replace('QR Code Value: ', '').trim();

                    // ดึง URL ของภาพ QR Code ที่สร้างขึ้นมา
                    const qrCodeImagePath = document.querySelector('#qrcode canvas').toDataURL('image/png');

                    // AJAX request เพื่อส่งข้อมูล QR Code ไปบันทึกในฐานข้อมูล
                    const xhr = new XMLHttpRequest();
                    const url = 'save_qr_code.php';
                    const params = `qr_code_value=${qrCodeValue}&qr_code_image=${qrCodeImagePath}`;

                    xhr.open('POST', url, true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                                alert('QR Code and data saved successfully!');
                            } else {
                                alert('Failed to save QR Code and data. Please try again.');
                            }
                        }
                    };

                    xhr.send(params);
                }
            </script>
        </div>
            </main>

        <script>
            let qrcodeInstance = null;

            function generateQRCode() {
                let qrCodeValue = '';

                // Generate random letters A-Z
                for (let i = 0; i < 2; i++) {
                    const randomChar = String.fromCharCode(65 + Math.floor(Math.random() * 26)); // 65 is ASCII for 'A'
                    qrCodeValue += randomChar;
                }

                // Append 3 random digits
                for (let i = 0; i < 3; i++) {
                    const randomDigit = Math.floor(Math.random() * 10); // 0-9
                    qrCodeValue += randomDigit;
                }

                const qrcodeContainer = document.getElementById('qrcode');
                qrcodeContainer.innerHTML = ''; // Clear previous content before generating new QR Code

                qrcodeInstance = new QRCode(qrcodeContainer, {
                    text: qrCodeValue,
                    width: 200, // Set width of QR Code (200 pixels)
                    height: 200, // Set height of QR Code (200 pixels)
                });

                const downloadBtn = document.getElementById('downloadBtn');
                downloadBtn.style.display = 'block';

                const printBtn = document.getElementById('printBtn');
                printBtn.style.display = 'block';

                const qrValueContainer = document.getElementById('qrValue');
                qrValueContainer.textContent = `QR Code Value: ${qrCodeValue}`;
            }

            function downloadQRCode() {
                const qrcodeCanvas = document.querySelector('#qrcode canvas');
                const qrValueContainer = document.getElementById('qrValue');
                const qrCodeValue = qrValueContainer.textContent.replace('QR Code Value: ', '').trim();

                const link = document.createElement('a');
                link.download = `qrcode_${qrCodeValue}.png`;
                link.href = qrcodeCanvas.toDataURL('image/png').replace('image/png', 'image/octet-stream');
                link.click();
            }

            function openPrintDialog() {
                window.print();
            }


           
        </script>
        <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="js/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/scripts.js"></script>
        <script src="js/simple-datatables@latest" type="text/javascript"></script>
        <script src="js/datatables/datatables-simple-demo.js"></script>
        <script src="js/litepicker/dist/bundle.js"></script>
        <script src="js/litepicker.js"></script>
</body>
</html>
