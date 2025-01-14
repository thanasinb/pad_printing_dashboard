let qrcodeInstance = null;

function createQRCode(qrcodeContainer, qrValueContainer) {
    let qrCodeValue = '';

    // Generate a QR Code value (customize this as per your requirement)
    for (let j = 0; j < 5; j++) {
        if (j < 2) {
            const randomChar = String.fromCharCode(65 + Math.floor(Math.random() * 26));
            qrCodeValue += randomChar;
        } else {
            const randomDigit = Math.floor(Math.random() * 10);
            qrCodeValue += randomDigit;
        }
    }

    // Create a div element for each QR Code item
    const qrcodeItemDiv = document.createElement('div');
    qrcodeItemDiv.classList.add('qrcode-item');

    // Create a QR Code and set its width and height
    const qrcode = new QRCode(qrcodeItemDiv, {
        text: qrCodeValue,
        width: 100,
        height: 100,
        correctLevel: QRCode.CorrectLevel.H
    });

    // Append the QR Code to the container
    qrcodeContainer.appendChild(qrcodeItemDiv);

    // Add the QR Code value to a div
    const qrValueDiv = document.createElement('div');
    qrValueDiv.classList.add('qr-code-value');
    qrValueDiv.textContent = `Code: ${qrCodeValue}`;
    qrcodeItemDiv.appendChild(qrValueDiv);
}

function generateQRCode() {
    const quantity = parseInt(document.getElementById('quantity').value);
    const qrcodeContainer = document.getElementById('qrcode');

    // ล้างเนื้อหาภายใน qrcodeContainer
    qrcodeContainer.innerHTML = '';

    // ซ่อนปุ่ม Download และ Print กลับไปเริ่มต้น
    document.getElementById('downloadBtn').style.display = 'none';
    document.getElementById('printBtn').style.display = 'none';

    // ตั้งค่าให้ปุ่ม Save โผล่มาเท่านั้น
    document.getElementById('saveBtn').style.display = 'inline-block';

    // จัดการการจัดเรียง QR Code ใน container
    if (quantity > 1) {
        qrcodeContainer.classList.remove('qrcode-center');
        qrcodeContainer.classList.add('qrcode-left');
    } else {
        qrcodeContainer.classList.remove('qrcode-left');
        qrcodeContainer.classList.add('qrcode-center');
    }

    // สร้าง QR Code ใหม่
    for (let i = 0; i < quantity; i++) {
        createQRCode(qrcodeContainer, null);
    }
}

async function saveQRCode() {
    const qrCodeValuesContainers = document.querySelectorAll('.qr-code-value');

    // ตรวจสอบว่ามี QR Codes อยู่หรือไม่
    if (!qrCodeValuesContainers || qrCodeValuesContainers.length === 0) {
        alert('No QR Codes to save. Please generate QR Codes first!');
        return; // หยุดทำงานหากไม่มี QR Codes
    }

    let successCount = 0;
    let errorCount = 0;
    let errorMessages = [];

    for (let index = 0; index < qrCodeValuesContainers.length; index++) {
        const qrCodeValueContainer = qrCodeValuesContainers[index];
        const qrCodeValue = qrCodeValueContainer.textContent.replace('Code: ', '').trim();
        const qrCodeCanvas = qrCodeValueContainer.parentNode.querySelector('canvas');

        // ตรวจสอบว่ามี Canvas หรือไม่
        if (!qrCodeCanvas) {
            errorCount++;
            errorMessages.push(`Canvas element not found for QR Code value: ${qrCodeValue}`);
            continue; // ข้ามรายการนี้หากไม่มี canvas
        }

        const qrCodeImagePath = qrCodeCanvas.toDataURL('image/png');

        try {
            const response = await fetch('save_qr_code.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `qr_code_value=${qrCodeValue}&qr_code_image=${encodeURIComponent(qrCodeImagePath)}`,
            });

            if (response.ok) {
                successCount++;
                console.log(`QR Code ${index + 1} saved successfully!`);
            } else if (response.status === 409) {
                errorCount++;
                errorMessages.push(`QR Code Value ${qrCodeValue} already exists.`);
            } else {
                errorCount++;
                errorMessages.push(`Failed to save QR Code ${qrCodeValue}. Please try again.`);
            }
        } catch (error) {
            console.error('Error saving QR Code:', error);
            errorCount++;
            errorMessages.push(`Failed to save QR Code ${qrCodeValue}. Please try again.`);
        }
    }

    if (errorCount === 0) {
        alert('All QR Codes saved successfully!');

        // ซ่อนปุ่ม Save และแสดงปุ่ม Download กับ Print
        document.getElementById('saveBtn').style.display = 'none';
        document.getElementById('downloadBtn').style.display = 'inline-block';
        document.getElementById('printBtn').style.display = 'inline-block';

    } else {
        let message = `Some QR Codes could not be saved. Success: ${successCount}, Errors: ${errorCount}\n\n` + errorMessages.join('\n');
        alert(message);
    }
}
async function saveDownloadHistory(downloadCount, downloadedCodes) {
    try {
        const response = await fetch('save_qr_code.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=download&count=${downloadCount}&qr_codes=${JSON.stringify(downloadedCodes)}`,
        });

        if (!response.ok) {
            console.error('Failed to save download history');
        }
    } catch (error) {
        console.error('Error saving download history:', error);
    }
}

async function downloadQRCode() {
    const { jsPDF } = window.jspdf; // โหลด jsPDF
    const pdf = new jsPDF(); // สร้างเอกสาร PDF ใหม่

    const qrcodeItems = document.querySelectorAll('.qrcode-item');
    let x = 10; // ตำแหน่งเริ่มต้นแกน X
    let y = 10; // ตำแหน่งเริ่มต้นแกน Y
    const qrSize = 50; // ขนาด QR Code ใน PDF
    const gap = 10; // ระยะห่างระหว่าง QR Codes
    const boxPadding = 5; // ระยะขอบภายในกรอบ

    qrcodeItems.forEach((item, index) => {
        const qrCodeValue = item.querySelector('.qr-code-value').textContent.replace('Code: ', '').trim();
        const qrCodeCanvas = item.querySelector('canvas');

        if (qrCodeCanvas) {
            const imgData = qrCodeCanvas.toDataURL('image/png'); // แปลง Canvas เป็น PNG

            // วาดกรอบรอบ QR Code
            pdf.rect(x - boxPadding, y - boxPadding, qrSize + boxPadding * 2, qrSize + boxPadding * 2 + 10); // +10 สำหรับข้อความ
            pdf.addImage(imgData, 'PNG', x, y, qrSize, qrSize); // เพิ่มภาพ QR Code ลงใน PDF
            pdf.text(`Code: ${qrCodeValue}`, x + qrSize / 2, y + qrSize + 10, { align: 'center' }); // เพิ่มข้อความใต้ QR Code

            // ปรับตำแหน่ง X และ Y สำหรับ QR Code ถัดไป
            x += qrSize + gap; // เลื่อนไปทางขวา
            if (x + qrSize > pdf.internal.pageSize.width - 10) { // ตรวจสอบว่าข้ามขอบกระดาษหรือไม่
                x = 10; // รีเซ็ตตำแหน่ง X
                y += qrSize + gap + 10; // ย้ายลงมาในแนว Y
            }

            // เพิ่มหน้ากระดาษใหม่หากพื้นที่ Y ไม่พอ
            if (y + qrSize > pdf.internal.pageSize.height - 10) {
                pdf.addPage(); // เพิ่มหน้าใหม่
                x = 10; // รีเซ็ตตำแหน่ง X
                y = 10; // รีเซ็ตตำแหน่ง Y
            }
        }
    });

    // บันทึกไฟล์ PDF
    pdf.save('QR_Codes.pdf');
}
function printQRCode() {
    const printContents = document.getElementById('qrcode').innerHTML;
    const originalContents = document.body.innerHTML;
    document.body.innerHTML = `<div id="printableArea">${printContents}</div>`;
    window.print();
    document.body.innerHTML = originalContents;
}

function showDownloadPrintButtons() {
    document.getElementById('downloadBtn').style.display = 'inline-block';
    document.getElementById('printBtn').style.display = 'inline-block';
}
async function checkSession() {
    try {
        const response = await fetch('pp-session-start.php', {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' } // แจ้งว่าเป็น AJAX Request
        });
        const result = await response.json();
        if (result.status === 'expired') {
            alert('Session หมดอายุแล้ว กรุณาเข้าสู่ระบบอีกครั้ง');
            window.location.href = 'pp-logout-session.php';
        }
    } catch (error) {
        console.error('Error checking session:', error);
    }
}

// เรียก checkSession ทุกครั้งที่ผู้ใช้กดคลิกหรือพิมพ์
document.addEventListener('click', () => checkSession());
document.addEventListener('input', () => checkSession());

// ฟังก์ชัน Ping Session เพื่อเช็คสถานะ Session จากเซิร์ฟเวอร์
async function pingSession() {
    try {
        const response = await fetch('pp-session-start.php', {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' } // บอกว่าเป็น AJAX Request
        });
        const result = await response.json();
        if (result.status === 'expired') {
            alert(result.message); // แจ้งผู้ใช้ว่า Session หมดอายุ
            window.location.href = 'pp-logout-session.php'; // Redirect ไปหน้า Login
        }
    } catch (error) {
        console.error('Error pinging session:', error);
    }
}

// เรียกฟังก์ชัน Ping Session ทุก 5 นาที (300,000 มิลลิวินาที)
setInterval(pingSession, 300000);

// ตรวจสอบทันทีเมื่อผู้ใช้กลับมาใช้งานหน้าเว็บ
document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'visible') {
        pingSession();
    }
});