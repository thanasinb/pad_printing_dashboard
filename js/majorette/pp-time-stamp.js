
    function updateClock() {
    var now = new Date();
    var hours = now.getHours();
    var minutes = now.getMinutes();
    var seconds = now.getSeconds();

    hours = hours < 10 ? '0' + hours : hours;
    minutes = minutes < 10 ? '0' + minutes : minutes;
    seconds = seconds < 10 ? '0' + seconds : seconds;

    document.getElementById('hours').textContent = hours;
    document.getElementById('minutes').textContent = minutes;
    document.getElementById('seconds').textContent = seconds;
}

    updateClock(); // เรียกใช้ฟังก์ชัน updateClock เพื่ออัปเดตเวลาแรกครั้ง
    setInterval(updateClock, 1000); // ใช้ setInterval เพื่ออัปเดตเวลาทุกๆ 1 วินาที
