async function checkSession() {
    try {
        const response = await fetch('pp-session-start.php', {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const result = await response.json();

        if (result.status === 'expired') {
            window.location.href = 'pp-homepage.php';
        }
    } catch (error) {
        console.error('Error checking session:', error);
    }
}
document.addEventListener('click', checkSession);
document.addEventListener('input', checkSession);
// เช็ค Session เมื่อผู้ใช้กลับมาใช้งาน หรือทุก 5 นาที
window.addEventListener('load', () => {
    checkSession();
});

document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'visible') {
        checkSession();
    }
});

setInterval(checkSession, 300000);  // ทุก 5 นาที (300,000 มิลลิวินาที)