<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate QR Code</title>

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-image: url('mj.png');
            background-size: cover;
            background-repeat: no-repeat;
        }

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
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        button {
            padding: 14px 24px;
            font-size: 18px;
            cursor: pointer;
            background-color: #0070c9;
            color: #fff;
            border: none;
            border-radius: 6px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            white-space: nowrap;
        }

        button:hover {
            background-color: #004d99;
        }

        #qrcode {
            margin-top: 20px;
        }

        #qrValue {
            margin-top: 20px;
            font-size: 18px;
            color: #666;
        }

        #downloadBtn, #printBtn, #anotherPageBtn {
            margin-top: 20px;
            padding: 14px 24px;
            font-size: 18px;
            cursor: pointer;
            background-color: #0070c9;
            color: #fff;
            border: none;
            border-radius: 6px;
            transition: background-color 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            white-space: nowrap;
        }

        #downloadBtn:hover, #printBtn:hover, #anotherPageBtn:hover {
            background-color: #004d99;
        }
    </style>


</head>
<body>
<div id="qrcodeContainer">
    <h1>Generate QR Code</h1>
    <button onclick="generateQRCode()">Generate QR Code</button>
    <div id="qrcode"></div>
    <div id="qrValue"></div>
    <button id="downloadBtn" onclick="downloadQRCode()">Download QR Code</button>
    <button id="printBtn" onclick="openPrintDialog()">Print QR Code</button>
    <button id="changePageBtn" onclick="changePage()">Go to Another Page</button>
</div>

<!-- Include QR Code library -->
<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
<script>
    let qrcodeInstance = null;

    function generateQRCode() {
        let qrCodeValue = '';

        // สร้างตัวอักษร A-Z
        for (let i = 0; i < 2; i++) {
            const randomChar = String.fromCharCode(65 + Math.floor(Math.random() * 26)); // 65 is ASCII for 'A'
            qrCodeValue += randomChar;
        }

        // เพิ่มตัวเลข 3 หลัก
        for (let i = 0; i < 3; i++) {
            const randomDigit = Math.floor(Math.random() * 10); // 0-9
            qrCodeValue += randomDigit;
        }

        const qrcodeContainer = document.getElementById('qrcode');
        qrcodeContainer.innerHTML = ''; // เคลียร์เนื้อหาของ qrcodeContainer ก่อนที่จะสร้าง QR Code ใหม่

        qrcodeInstance = new QRCode(qrcodeContainer, {
            text: qrCodeValue,
            width: 200, // กำหนดความกว้างของ QR Code (200 pixels)
            height: 200, // กำหนดความสูงของ QR Code (200 pixels)
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

    function changePage() {
        // Redirect to another page (example: Google homepage)
        window.location.href = 'https://www.google.com';
    }
</script>
</body>
</html>
