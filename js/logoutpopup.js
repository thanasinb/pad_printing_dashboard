// ไฟล์ js/logoutpopup.js
function confirmLogout() {
    // ใช้ confirm() เพื่อแสดงกรอบยืนยันและรับผลลัพธ์จากผู้ใช้
    var confirmLogout = confirm("ต้องการออกจากระบบใช่ไหม?");

    // ตรวจสอบผลลัพธ์จาก confirm()
    if (confirmLogout) {
        // ถ้าผู้ใช้ต้องการออกจากระบบ ให้เปลี่ยนเส้นทางไปยัง pp-homepage.php
        window.location.href = "pp-homepage.php";
    }
    // ถ้าผู้ใช้ยกเลิกการออกจากระบบ ไม่ต้องทำอะไรเพิ่มเติม
}
