document.addEventListener('DOMContentLoaded', function () {

    function formatDate(dateString) {
        if (!dateString || dateString.trim() === "") return "N/A";
        const [year, month, day] = dateString.split('-');
        return `${day}/${month}/${year}`;
    }
    const ctxDowntime = document.getElementById('downtimeChart').getContext('2d');

    const machines = JSON.parse(document.getElementById('machines').value);
    let downtimeDurations = JSON.parse(document.getElementById('downtimeDurations').value);
    let downtimeDetails = JSON.parse(document.getElementById('downtimeDetails').value);
    let currentDowntimeDurations = [...downtimeDurations]; // เก็บสถานะปัจจุบันของ downtimeDurations

    // ฟังก์ชันสำหรับดึงสีที่เหมาะสมกับธีม
    function getThemeColors() {
        const isDarkMode = document.body.classList.contains('dark-mode');
        return {
            textColor: isDarkMode ? 'white' : 'black',
            gridColor: isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)',
        };
    }

    // ฟังก์ชันสร้างกราฟ
    function createDowntimeChart(machines, durations) {
        const themeColors = getThemeColors();
        return new Chart(ctxDowntime, {
            type: 'bar',
            data: {
                labels: machines,
                datasets: [{
                    label: 'Total Downtime (hours)',
                    data: durations,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                onClick: (evt, activeElements) => {
                    if (activeElements.length > 0) {
                        const dataIndex = activeElements[0].index;
                        const machine = machines[dataIndex];
                        const details = downtimeDetails[machine];
                        const totalDowntimeForMachine = durations[dataIndex];

                        selectedStartDate = document.getElementById('downtime_start_date').value;
                        selectedEndDate = document.getElementById('downtime_end_date').value;

                        let detailsContent = '';
                        if (details) {
                            details.forEach(detail => {
                                detailsContent += `
                                   
                                    <strong>Downtime Code:</strong> ${detail.id_code_downtime}, 
                                    <strong>Duration:</strong> ${detail.downtime_duration} hours<br>`;
                            });
                        } else {
                            detailsContent = 'No downtime details available.';
                        }

                        const modalContent = `
                            <strong>Selected Date Range:</strong> ${formatDate(selectedStartDate)} to ${formatDate(selectedEndDate)}<br>
                            <strong>Machine:</strong> ${machine}<br>
                            <strong>Total Downtime:</strong> ${totalDowntimeForMachine.toFixed(2)} hours<br>
                            <strong>Downtime Details:</strong><br>${detailsContent}
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
                            text: 'Total Downtime (hours)',
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
                            autoSkip: false,  // ✅ ปิดการข้ามป้ายกำกับอัตโนมัติ
                            maxRotation: 180,   // ✅ บังคับให้ตัวอักษรตรง
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

    let downtimeChart = createDowntimeChart(machines, currentDowntimeDurations);

    // อัปเดตกราฟและข้อมูลเมื่อเปลี่ยนช่วงเวลา
    document.getElementById('downtimeDateForm').addEventListener('submit', function (event) {
        event.preventDefault();
        const startDate = document.getElementById('downtime_start_date').value;
        const endDate = document.getElementById('downtime_end_date').value;
      
        fetch(`get_downtime_data.php?startDate=${startDate}&endDate=${endDate}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                downtimeDetails = data.downtimeDetails;

                currentDowntimeDurations = machines.map(machine => {
                    const machineDetails = downtimeDetails[machine] || [];
                    return machineDetails.reduce((sum, detail) => sum + parseFloat(detail.downtime_duration || 0), 0);
                });

                const totalDowntime = currentDowntimeDurations.reduce((sum, duration) => sum + duration, 0);
                document.getElementById('totalDowntime').textContent = totalDowntime.toFixed(2);

                if(downtimeChart){
                    downtimeChart.destroy();
                }
                downtimeChart = createDowntimeChart(machines, currentDowntimeDurations);
            })
            .catch(error => console.error('Error fetching data:', error));
    });

    // ฟังก์ชันอัปเดตธีมเมื่อเปลี่ยนโหมด
    // const observer = new MutationObserver(() => {
    //     downtimeChart.destroy();
    //     downtimeChart = createDowntimeChart(machines, currentDowntimeDurations);
    // });
    //
    // observer.observe(document.body, { attributes: true, attributeFilter: ['class'] });
});