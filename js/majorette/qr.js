async function checkQRCodeExists(qrCodeValue) {
    const response = await fetch('check_qr_code.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `qr_code_value=${qrCodeValue}`
    });

    const result = await response.json();
    return result.exists; // Assuming the response JSON has an "exists" field
}

async function generateUniqueQRCode() {
    let qrCodeValue = '';
    let exists = true;

    while (exists) {
        qrCodeValue = '';
        for (let j = 0; j < 5; j++) { // สร้างตัวอักษรและตัวเลขที่ยาวกว่า
            if (j < 2) {
                const randomChar = String.fromCharCode(65 + Math.floor(Math.random() * 26));
                qrCodeValue += randomChar;
            } else {
                const randomDigit = Math.floor(Math.random() * 10);
                qrCodeValue += randomDigit;
            }
        }

        exists = await checkQRCodeExists(qrCodeValue);
    }

    return qrCodeValue;
}

async function createQRCode(qrcodeContainer) {
    const qrCodeValue = await generateUniqueQRCode();

    // สร้าง element div สำหรับแสดง QR Code
    const qrcodeItemDiv = document.createElement('div');
    qrcodeItemDiv.classList.add('qrcode-item');

    // สร้าง QR Code และกำหนดความกว้างและความสูง
    const qrcode = new QRCode(qrcodeItemDiv, {
        text: qrCodeValue,
        width: 200, // ความกว้างของ QR Code (สามารถปรับตามต้องการ)
        height: 200, // ความสูงของ QR Code
        correctLevel: QRCode.CorrectLevel.H // ระดับการแก้ไขของ QR Code (High)
    });

    // เพิ่ม QR Code ลงใน container
    qrcodeContainer.appendChild(qrcodeItemDiv);

    // เพิ่มค่า QR Code ลงใน div
    const qrValueDiv = document.createElement('div');
    qrValueDiv.classList.add('qr-code-value');
    qrValueDiv.textContent = `QR Code Value: ${qrCodeValue}`;
    qrcodeItemDiv.appendChild(qrValueDiv);
}

async function generateQRCode() {
    const quantity = parseInt(document.getElementById('quantity').value);
    const qrcodeContainer = document.getElementById('qrcode');
    qrcodeContainer.innerHTML = '';

    for (let i = 0; i < quantity; i++) {
        await createQRCode(qrcodeContainer);
    }
}

function saveQRCode() {
    const qrCodeValuesContainers = document.querySelectorAll('.qr-code-value');

    qrCodeValuesContainers.forEach(qrCodeValueContainer => {
        const qrCodeValue = qrCodeValueContainer.textContent.replace('QR Code Value: ', '').trim();
        const qrCodeImagePath = qrCodeValueContainer.parentNode.querySelector('canvas').toDataURL('image/png');

        const xhr = new XMLHttpRequest();
        const url = 'save_qr_code.php';
        const params = `qr_code_value=${qrCodeValue}&qr_code_image=${qrCodeImagePath}`;
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log('QR Code and data saved successfully!');
                } else if (xhr.status === 409) {
                    console.error('Duplicate QR Code value. This QR Code will not be saved.');
                } else {
                    console.error('Failed to save QR Code and data. Please try again.');
                }
            }
        };
        xhr.send(params);
    });

    alert('QR Codes and data are being saved.');
}
