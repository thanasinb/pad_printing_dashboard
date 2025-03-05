function previewNewImage() {
    const file = document.getElementById('imageFile').files[0];
    const reader = new FileReader();

    reader.onload = function (e) {
        document.getElementById('preview').src = e.target.result;
        document.getElementById('uploadButton').style.display = 'inline-block'; // แสดงปุ่ม upload
        document.getElementById('cancelButton').style.display = 'inline-block'; // แสดงปุ่ม cancel
    };

    if (file) {
        reader.readAsDataURL(file);
    }
}

$('#uploadImageForm').on('submit', function (e) {
    e.preventDefault(); // ป้องกันการส่งฟอร์มแบบปกติ
    var formData = new FormData(this);

    $.ajax({
        url: 'upload.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log("Response:", response);

            if (response.startsWith("ERROR")) {
                alert(response);
            } else {
                alert("Profile updated successfully!"); // แจ้งเตือนก่อนรีเฟรช
                location.reload(); // รีเฟรชหน้าเว็บ
            }
        },
        error: function () {
            alert('Error uploading image.');
        }
    });
});
function refreshProfileImage() {
    const profileImage = document.getElementById('preview');
    const newSrc = profileImage.src.split('?')[0] + '?t=' + new Date().getTime();
    profileImage.src = newSrc;
}

function cancelImage() {
    // รีเซ็ตภาพกลับไปที่ภาพเดิม
    document.getElementById('preview').src = "<?php echo $profileImagePath; ?>";
    document.getElementById('uploadButton').style.display = 'none'; // ซ่อนปุ่ม upload
    document.getElementById('cancelButton').style.display = 'none'; // ซ่อนปุ่ม cancel
    document.getElementById('imageFile').value = ''; // ล้างค่าไฟล์ที่ถูกเลือก
}
let cropper;

function startCrop() {
    const imageFile = document.getElementById('imageFile').files[0];
    const reader = new FileReader();

    reader.onload = function (event) {
        const image = document.getElementById('imageToCrop');
        image.src = event.target.result;
        document.getElementById('cropContainer').style.display = 'flex'; // แสดงพื้นที่ครอบภาพ

        if (cropper) {
            cropper.destroy();  // ทำลาย cropper เก่าถ้ามี
        }

        cropper = new Cropper(image, {
            aspectRatio: 1, // กำหนดสัดส่วนเป็น 1:1
            viewMode: 1,
            background: false,
            zoomable: true,
            movable: true,
            scalable: true,
            ready: function () {
                // ทำให้ crop box เป็นวงกลมโดยใช้ CSS
                const cropBox = document.querySelector('.cropper-crop-box');
                cropBox.style.borderRadius = '50%'; // เพิ่มการทำให้เป็นวงกลม
                cropBox.style.border = '2px solid white'; // เพิ่มขอบสีขาว
            }
        });
    };

    if (imageFile) {
        reader.readAsDataURL(imageFile);
    }
}

document.getElementById('cropButton').addEventListener('click', function () {
    // ครอบภาพและแสดงใน preview
    const croppedCanvas = cropper.getCroppedCanvas({
        width: 300,
        height: 300,
    });

    // สร้างภาพเป็นวงกลมใน canvas
    const circleCanvas = document.createElement('canvas');
    circleCanvas.width = 300;
    circleCanvas.height = 300;
    const ctx = circleCanvas.getContext('2d');

    // วาดภาพครอบเป็นวงกลม
    ctx.beginPath();
    ctx.arc(150, 150, 150, 0, Math.PI * 2); // วาดวงกลมกลาง
    ctx.closePath();
    ctx.clip(); // ตัดภาพให้เป็นวงกลม

    ctx.drawImage(croppedCanvas, 0, 0, 300, 300);

    // แปลง canvas เป็น Data URL สำหรับแสดงผลและอัปโหลด
    const croppedImageDataUrl = circleCanvas.toDataURL('image/png');

    // แสดงภาพครอบใน preview ด้านบน
    document.getElementById('preview').src = croppedImageDataUrl;
    document.getElementById('cropContainer').style.display = 'none'; // ซ่อนพื้นที่ครอบ
    document.getElementById('uploadButton').style.display = 'block'; // แสดงปุ่มอัปโหลด

    // ส่งภาพที่ครอบไปยังเซิร์ฟเวอร์เมื่อกดปุ่มอัปโหลด
    document.getElementById('uploadImageForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData();
        formData.append('croppedImage', croppedImageDataUrl);

        $.ajax({
            url: 'upload.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.startsWith("ERROR")) {
                    alert(response);
                } else {
                    document.getElementById('preview').src = response; // เปลี่ยนภาพโปรไฟล์เป็นภาพใหม่
                }
            },
            error: function () {
                alert('Error uploading image');
            }
        });
    });
});

function cancelCrop() {
    document.getElementById('cropContainer').style.display = 'none'; // ซ่อนพื้นที่ครอบ
    document.getElementById('imageFile').value = ''; // ล้างค่าไฟล์ที่ถูกเลือก

}