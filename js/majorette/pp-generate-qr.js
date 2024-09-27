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
    qrcodeContainer.innerHTML = '';

    // เมื่อมี QR code มากกว่า 1 ให้เปลี่ยนการจัดเรียงเป็นจากซ้ายไปขวา
    if (quantity > 1) {
        qrcodeContainer.classList.remove('qrcode-center');
        qrcodeContainer.classList.add('qrcode-left');
    } else {
        qrcodeContainer.classList.remove('qrcode-left');
        qrcodeContainer.classList.add('qrcode-center');
    }

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
        const qrCodeValue = qrCodeValueContainer.textContent.replace('Code: ', '').trim();
        const qrCodeImagePath = qrCodeValueContainer.parentNode.querySelector('canvas').toDataURL('image/png');

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
        showDownloadPrintButtons(); // Show download and print buttons
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
    const qrcodeItems = document.querySelectorAll('.qrcode-item');
    let downloadCount = 0;
    let downloadedCodes = [];

    qrcodeItems.forEach((item, index) => {
        const qrCodeValue = item.querySelector('.qr-code-value').textContent.replace('Code: ', '').trim();
        const qrcodeCanvas = item.querySelector('canvas');
        if (qrcodeCanvas) {
            const link = document.createElement('a');
            link.download = `qrcode_${qrCodeValue}.png`;
            link.href = qrcodeCanvas.toDataURL('image/png').replace('image/png', 'image/octet-stream');
            link.click();
            downloadedCodes.push(qrCodeValue);
            downloadCount++;
        } else {
            console.error(`Canvas element not found for QR Code value: ${qrCodeValue}`);
        }
    });

    if (downloadCount > 0) {
        await saveDownloadHistory(downloadCount, downloadedCodes);
    }
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
