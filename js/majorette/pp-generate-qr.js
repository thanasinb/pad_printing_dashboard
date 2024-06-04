let qrcodeInstance = null;

function createQRCode(qrcodeContainer, qrValueContainer) {
    let qrCodeValue = '';

    // สร้างข้อความ QR Code ที่ยาวขึ้น
    for (let j = 0; j < 5; j++) { // สร้างตัวอักษรและตัวเลขที่ยาวกว่า
        if (j < 2) {
            const randomChar = String.fromCharCode(65 + Math.floor(Math.random() * 26));
            qrCodeValue += randomChar;
        } else {
            const randomDigit = Math.floor(Math.random() * 10);
            qrCodeValue += randomDigit;
        }
    }

    // สร้าง element div สำหรับแสดง QR Code
    const qrcodeItemDiv = document.createElement('div');
    qrcodeItemDiv.classList.add('qrcode-item');

    // สร้าง QR Code และกำหนดความกว้างและความสูง
    const qrcode = new QRCode(qrcodeItemDiv, {
        text: qrCodeValue,
        width: 100, // ความกว้างของ QR Code (สามารถปรับตามต้องการ)
        height: 100, // ความสูงของ QR Code
        correctLevel: QRCode.CorrectLevel.H // ระดับการแก้ไขของ QR Code (High)
    });

    // เพิ่ม QR Code ลงใน container
    qrcodeContainer.appendChild(qrcodeItemDiv);

    // เพิ่มค่า QR Code ลงใน div
    const qrValueDiv = document.createElement('div');
    qrValueDiv.classList.add('qr-code-value');
    qrValueDiv.textContent = `Code:${qrCodeValue}`;
    qrcodeItemDiv.appendChild(qrValueDiv);
}

function generateQRCode() {
    const quantity = parseInt(document.getElementById('quantity').value);
    const qrcodeContainer = document.getElementById('qrcode');
    qrcodeContainer.innerHTML = '';

    for (let i = 0; i < quantity; i++) {
        createQRCode(qrcodeContainer, null);
    }
}

async function saveQRCode() {
    const qrCodeValuesContainers = document.querySelectorAll('.qr-code-value');
    let successCount = 0;
    let errorCount = 0;
    let errorMessages = [];

    for (let index = 0; index < qrCodeValuesContainers.length; index++) {
        const qrCodeValueContainer = qrCodeValuesContainers[index];
        const qrCodeValue = qrCodeValueContainer.textContent.replace('Code:', '').trim();
        const qrCodeImagePath = qrCodeValueContainer.parentNode.querySelector('canvas').toDataURL('image/png');

        const xhr = new XMLHttpRequest();
        const url = 'save_qr_code.php';
        const params = `qr_code_value=${qrCodeValue}&qr_code_image=${qrCodeImagePath}`;

        await new Promise((resolve, reject) => {
            xhr.open('POST', url, true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        successCount++;
                        console.log(`QR Code ${index + 1} บันทึกเรียบร้อยแล้ว!`);
                        resolve();
                    } else if (xhr.status === 409) {
                        errorCount++;
                        errorMessages.push(`QR Code Value ${qrCodeValue} ซ้ำ`);
                        resolve();
                    } else {
                        errorCount++;
                        errorMessages.push(`ไม่สามารถบันทึกรหัสได้ ${qrCodeValue}. โปรดลองอีกครั้ง.`);
                        resolve();
                    }
                }
            };
            xhr.send(params);
        });
    }

    if (errorCount === 0) {
        alert('บันทึกรหัส Qr ทั้งหมดเรียบร้อยแล้ว!');
    } else {
        let message = `Some QR Codes could not be saved. Success: ${successCount}, Errors: ${errorCount}\n\n` + errorMessages.join('\n');
        alert(message);
    }
}


// function saveQRCode() {
//     const qrCodeValuesContainers = document.querySelectorAll('.qr-code-value');
//     let successCount = 0;
//     let errorCount = 0;
//     let errorMessages = [];
//
//     qrCodeValuesContainers.forEach((qrCodeValueContainer, index) => {
//         const qrCodeValue = qrCodeValueContainer.textContent.replace('Code:', '').trim();
//         const qrCodeImagePath = qrCodeValueContainer.parentNode.querySelector('canvas').toDataURL('image/png');
//
//         const xhr = new XMLHttpRequest();
//         const url = 'save_qr_code.php';
//         const params = `qr_code_value=${qrCodeValue}&qr_code_image=${qrCodeImagePath}`;
//         xhr.open('POST', url, true);
//         xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
//         xhr.onreadystatechange = function() {
//             if (xhr.readyState === XMLHttpRequest.DONE) {
//                 if (xhr.status === 200) {
//                     successCount++;
//                     console.log(`QR Code ${index + 1} บันทึกเรียบร้อยแล้ว!`);
//                 } else if (xhr.status === 409) {
//                     errorCount++;
//                     errorMessages.push(`QR Code Value ${qrCodeValue} ซ้ำ`);
//                 } else {
//                     errorCount++;
//                     errorMessages.push(`ไม่สามารถบันทึกรหัสได้ ${qrCodeValue}. โปรดลองอีกครั้ง.`);
//                 }
//
//                 // Check if this is the last request
//                 if (index === qrCodeValuesContainers.length - 1) {
//                     if (errorCount === 0) {
//                         alert('บันทึกรหัส Qr ทั้งหมดเรียบร้อยแล้ว!');
//                     } else {
//                         let message = `Some QR Codes could not be saved. Success: ${successCount}, Errors: ${errorCount}\n\n` + errorMessages.join('\n');
//                         alert(message);
//                     }
//                 }
//             }
//         };
//         xhr.send(params);
//     });
// }





function downloadQRCode() {
    const qrcodeItems = document.querySelectorAll('.qrcode-item');
    qrcodeItems.forEach((item, index) => {
        const qrCodeValue = item.querySelector('.qr-code-value').textContent.replace('Code:', '').trim();
        const qrcodeCanvas = item.querySelector('canvas');
        if (qrcodeCanvas) { // Check if the canvas exists
            const link = document.createElement('a');
            link.download = `qrcode_${qrCodeValue}.png`;
            link.href = qrcodeCanvas.toDataURL('image/png').replace('image/png', 'image/octet-stream');
            link.click();
        } else {
            console.error(`Canvas element not found for QR Code value: ${qrCodeValue}`);
        }
    });
}
function printQRCode() {
    const printContents = document.getElementById('qrcode').innerHTML;
    const originalContents = document.body.innerHTML;
    document.body.innerHTML = `<div id="printableArea">${printContents}</div>`;
    window.print();
    document.body.innerHTML = originalContents;
}
