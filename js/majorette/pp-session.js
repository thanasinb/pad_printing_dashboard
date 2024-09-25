// สร้าง XMLHttpRequest object
var xhr = new XMLHttpRequest();

// กำหนดการเชื่อมต่อเป็นแบบ asynchronous (เชื่อมต่อแบบไม่รอคอย)
xhr.open("GET", "pp-session-check.php", true);

// กำหนด callback function เมื่อคำร้องขอเสร็จสมบูรณ์
xhr.onreadystatechange = function () {
    // ตรวจสอบสถานะการเรียกคำร้องขอ
    if (xhr.readyState === XMLHttpRequest.DONE) {
        // ตรวจสอบสถานะการตอบกลับจากเซิร์ฟเวอร์
        if (xhr.status === 200) {
            // รับข้อมูลที่ตอบกลับมาจากเซิร์ฟเวอร์
            var response = xhr.responseText;
            // ดำเนินการต่อไปตามเงื่อนไขที่ได้รับ
            if (response === "session_expired") {
                // กรณี Session หมดอายุ
                alert("Session หมดอายุแล้ว");
                window.location.href = 'pp-logout-session.php';
            }
        } else {
            // หากมีปัญหาในการเชื่อมต่อกับเซิร์ฟเวอร์
            console.log('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์');
        }
    }
};

// ส่งคำร้องขอไปยังเซิร์ฟเวอร์
xhr.send();
