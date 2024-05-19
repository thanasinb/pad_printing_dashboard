let qrcodeInstance = null;

function generateQRCode() {
    const quantity = parseInt(document.getElementById('quantity').value);
    const qrcodeContainer = document.getElementById('qrcodeContainer');
    const qrValueContainer = document.getElementById('qrValue');

    // Clear previous QR codes
    qrcodeContainer.innerHTML = '';
    qrValueContainer.innerHTML = '';

    for (let i = 0; i < quantity; i++) {
        let qrCodeValue = generateQRCodeValue();

        // Create a div element to contain the QR Code
        const qrcodeDiv = document.createElement('div');
        qrcodeDiv.className = 'qrcode-item';
        qrcodeDiv.id = `qrcode-${i}`; // Unique ID for each QR code container

        // Generate the QR Code with specified width and height
        const qrcode = new QRCode(qrcodeDiv, {
            text: qrCodeValue,
            width: 200,
            height: 200,
            correctLevel: QRCode.CorrectLevel.H
        });

        // Append the QR Code to the container
        qrcodeContainer.appendChild(qrcodeDiv);

        // Display the QR Code value
        qrValueContainer.innerHTML += `<div>QR Code Value: ${qrCodeValue}</div>`;
    }
}

function generateQRCodeValue() {
    let qrCodeValue = '';
    for (let j = 0; j < 5; j++) {
        if (j < 2) {
            const randomChar = String.fromCharCode(65 + Math.floor(Math.random() * 26));
            qrCodeValue += randomChar;
        } else {
            const randomDigit = Math.floor(Math.random() * 10);
            qrCodeValue += randomDigit;
        }
    }
    return qrCodeValue;
}

// แบ่งฟังก์ชัน saveQRCode(), downloadQRCode(), และ openPrintDialog() ไว้ต่อไป



function saveQRCode() {
    const qrValueContainers = document.querySelectorAll('#qrValue div');
    const qrCodeValues = Array.from(qrValueContainers).map(container => container.textContent.replace('QR Code Value: ', '').trim());
    const qrcodeCanvases = document.querySelectorAll('#qrcode canvas');

    qrcodeCanvases.forEach((canvas, index) => {
        const qrCodeValue = qrCodeValues[index];
        const qrCodeImagePath = canvas.toDataURL('image/png');
        const xhr = new XMLHttpRequest();
        const url = 'save_qr_code.php';
        const params = `qr_code_value=${qrCodeValue}&qr_code_image=${encodeURIComponent(qrCodeImagePath)}`;

        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    alert(`QR Code and data saved successfully for ${qrCodeValue}!`);
                } else {
                    alert('Failed to save QR Code and data. Please try again.');
                }
            }
        };
        xhr.send(params);
    });
}

function downloadQRCode() {
    const qrValueContainers = document.querySelectorAll('#qrValue div');
    qrValueContainers.forEach((container, index) => {
        const qrCodeValue = container.textContent.replace('QR Code Value: ', '').trim();
        const qrcodeCanvas = document.querySelectorAll('#qrcode canvas')[index];
        const link = document.createElement('a');
        link.download = `qrcode_${qrCodeValue}.png`;
        link.href = qrcodeCanvas.toDataURL('image/png').replace('image/png', 'image/octet-stream');
        link.click();
    });
}

function openPrintDialog() {
    const printContents = document.getElementById('qrcode').innerHTML;
    const originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
