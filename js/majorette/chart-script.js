document.addEventListener('DOMContentLoaded', function () {

    function formatDate(dateString) {
        if (!dateString) return "N/A";  // ถ้ายังไม่มีค่า ให้แสดง N/A
        const [year, month, day] = dateString.split('-');
        return `${day}/${month}/${year}`;
    }
    const ctx = document.getElementById('machineChart').getContext('2d');

    // เก็บข้อมูลปัจจุบันในตัวแปรสถานะ
    let currentMachines = JSON.parse(document.getElementById('machines').value);
    let currentJobCounts = JSON.parse(document.getElementById('jobCounts').value);
    let currentTaskDetails = JSON.parse(document.getElementById('taskDetails').value);

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
                        const machine = currentMachines[index];
                        const jobCount = currentJobCounts[index];
                        const tasks = currentTaskDetails[index].join('<br>');

                        selectedStartDate = document.getElementById('start_date').value;
                        selectedEndDate = document.getElementById('end_date').value;
                        const modalContent = `
                            <strong>Selected Date Range:</strong> ${formatDate(selectedStartDate)} to ${formatDate(selectedEndDate)}<br>
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
    let machineChart = createChart(currentMachines, currentJobCounts, currentTaskDetails);

    // ฟอร์มสำหรับโหลดข้อมูลใหม่
    document.getElementById('dateForm').addEventListener('submit', function (event) {
        event.preventDefault();
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        const start = new Date(startDate);
        const end = new Date(endDate);

        if (isNaN(start.getTime()) || isNaN(end.getTime())) {
            alert('โปรดระบุวันที่ในรูปแบบที่ถูกต้อง');
            return;
        }
        if (start > end) {
            alert('โปรดระบุช่วงวันให้ถูกต้อง');
            return;
        }
        // if (end > today) {
        //     alert('โปรดระบุช่วงวันให้ถูกต้องให้อยู่ภายในวันปัจจุบัน');
        //     return;
        // }
        fetch(`get_data.php?startDate=${startDate}&endDate=${endDate}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                // อัปเดตข้อมูลปัจจุบัน
                currentMachines = data.machines;
                currentJobCounts = data.jobCounts;
                currentTaskDetails = data.taskDetails;

                // ทำลายกราฟเดิมและสร้างกราฟใหม่ด้วยข้อมูลที่อัปเดต
                if(machineChart) {
                    machineChart.destroy();
                }
                machineChart = createChart(currentMachines, currentJobCounts, currentTaskDetails);
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                alert('Error fetching data. Please check the console for more details.');
            });
    });


});
