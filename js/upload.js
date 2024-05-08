function displayImage(input) {
    const preview = document.getElementById('previewImage');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result; // แสดงตัวอย่างของรูปที่ผู้ใช้เลือก
        };
        reader.readAsDataURL(input.files[0]); // อ่านไฟล์เป็น URL data
    } else {
        preview.src = '/projects/mjrqr/assets/img/illustrations/profiles/profile-1.png'; // ใช้รูปเดิมหากไม่มีการเลือกไฟล์
    }
}
