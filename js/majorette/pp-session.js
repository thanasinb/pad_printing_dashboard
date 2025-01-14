async function checkSession() {
    try {
        const response = await fetch('pp-session-start.php', {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' } // บอกว่าเป็น AJAX Request
        });
        const result = await response.json();
        if (result.status === 'expired') {
            alert('Session ของคุณหมดอายุแล้ว กรุณาเข้าสู่ระบบอีกครั้ง');
            window.location.href = 'pp-logout-session.php'; // เด้งไปหน้า Login
        }
    } catch (error) {
        console.error('Error checking session:', error);
    }
}
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
document.addEventListener('click', () => checkSession());
document.addEventListener('input', () => checkSession());