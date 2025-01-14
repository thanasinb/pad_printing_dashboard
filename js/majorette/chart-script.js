document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('machineChart').getContext('2d');

    const machines = JSON.parse(document.getElementById('machines').value);
    const jobCounts = JSON.parse(document.getElementById('jobCounts').value);
    const taskDetails = JSON.parse(document.getElementById('taskDetails').value);

    // ฟังก์ชันสำหรับดึงสีที่เหมาะสมกับธีม
    function getThemeColors() {
        const isDarkMode = document.body.classList.contains('dark-mode');
        return {
            textColor: isDarkMode ? 'white' : 'black',
            gridColor: isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'
        };
    }

    // ฟังก์ชันสร้างกราฟ
    function createChart(machines, jobCounts, taskDetails) {
        const themeColors = getThemeColors();
        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: machines,
                datasets: [{
                    label: 'Total of Jobs',
                    data: jobCounts,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                onClick: function (evt, elements) {
                    if (elements.length > 0) {
                        const element = elements[0];
                        const index = element.index;
                        const machine = machines[index];
                        const jobCount = jobCounts[index];
                        const tasks = taskDetails[index].join('<br>');

                        const modalContent = `
                            <strong>Machine:</strong> ${machine}<br>
                            <strong>Total of Jobs:</strong> ${jobCount}<br>
                            <strong>ID Tasks:</strong><br> ${tasks}
                        `;

                        document.getElementById('modalContent').innerHTML = modalContent;
                        $('#detailModal').modal('show');
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Total of Jobs',
                            color: themeColors.textColor
                        },
                        ticks: {
                            color: themeColors.textColor
                        },
                        grid: {
                            color: themeColors.gridColor
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Machines',
                            color: themeColors.textColor
                        },
                        ticks: {
                            color: themeColors.textColor,
                            autoSkip: false,
                            maxRotation: 90,
                            minRotation: 90
                        },
                        grid: {
                            color: themeColors.gridColor
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: themeColors.textColor
                        }
                    }
                }
            }
        });
    }

    // สร้างกราฟด้วยข้อมูลเริ่มต้น
    let machineChart = createChart(machines, jobCounts, taskDetails);

    // ฟอร์มสำหรับโหลดข้อมูลใหม่
    document.getElementById('dateForm').addEventListener('submit', function (event) {
        event.preventDefault();
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;

        fetch(`get_data.php?startDate=${startDate}&endDate=${endDate}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }
                const newMachines = data.machines;
                const newJobCounts = data.jobCounts;
                const newTaskDetails = data.taskDetails;

                // ทำลายกราฟเดิมและสร้างกราฟใหม่ด้วยข้อมูลที่อัปเดต
                machineChart.destroy();
                machineChart = createChart(newMachines, newJobCounts, newTaskDetails);
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                alert('Error fetching data. Please check the console for more details.');
            });
    });

    // ฟังก์ชันอัปเดตธีมเมื่อเปลี่ยนโหมด
    const observer = new MutationObserver(() => {
        machineChart.destroy(); // ลบกราฟเดิม
        machineChart = createChart(machines, jobCounts, taskDetails); // สร้างกราฟใหม่
    });

    observer.observe(document.body, { attributes: true, attributeFilter: ['class'] });
});