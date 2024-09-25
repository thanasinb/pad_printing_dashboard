document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('machineChart').getContext('2d');

    // ข้อมูลตัวอย่างสำหรับกราฟ
    const machineData = {
        labels: Array.from({length: 40}, (_, i) => `Machine ${i + 1}`), // Labels สำหรับแต่ละเครื่อง
        datasets: [{
            label: 'Number of Jobs',
            data: [450, 560, 650, 540, 760, 870, 980, 500, 620, 730, 540, 620, 750, 690, 840, 910, 560, 710, 760, 820, 600, 700, 800, 900, 550, 680, 720, 810, 870, 930, 490, 620, 730, 830, 920, 650, 700, 760, 830, 880], // ข้อมูลตัวอย่าง
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }]
    };

    const machineChart = new Chart(ctx, {
        type: 'bar',
        data: machineData,
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Jobs'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Machines'
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Machine Statistics'
                }
            }
        }
    });
});
