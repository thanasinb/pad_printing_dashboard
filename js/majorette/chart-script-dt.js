document.addEventListener('DOMContentLoaded', function () {
    const ctxDowntime = document.getElementById('downtimeChart').getContext('2d');

    const machines = JSON.parse(document.getElementById('machines').value);
    const downtimeDurations = JSON.parse(document.getElementById('downtimeDurations').value);
    let downtimeDetails = JSON.parse(document.getElementById('downtimeDetails').value);

    function getThemeColors() {
        const isDarkMode = document.body.classList.contains('dark-mode');
        return {
            textColor: isDarkMode ? 'white' : 'black',
            gridColor: isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)',
        };
    }

    function createDowntimeChart(machines, downtimeDurations) {
        const themeColors = getThemeColors();
        return new Chart(ctxDowntime, {
            type: 'bar',
            data: {
                labels: machines,
                datasets: [{
                    label: 'Total Downtime (hours)',
                    data: downtimeDurations,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                onClick: function (evt, elements) {
                    if (elements.length > 0) {
                        const element = elements[0];
                        const index = element.index;
                        const machine = machines[index];
                        const details = downtimeDetails[machine];
                        let detailsContent = '';
                        if (details) {
                            details.forEach(detail => {
                                detailsContent += `<strong>Downtime Code:</strong> ${detail.id_code_downtime}, <strong>Duration:</strong> ${detail.downtime_duration.toFixed(2)} hours<br>`;
                            });
                        } else {
                            detailsContent = 'No downtime details available.';
                        }

                        const modalContent = `
                            <strong>Machine:</strong> ${machine}<br>
                            <strong>Total Downtime:</strong> ${downtimeDurations[index].toFixed(2)} hours<br>
                            <strong>Downtime Details:</strong><br> ${detailsContent}
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

    let downtimeChart = createDowntimeChart(machines, downtimeDurations);

    // อัปเดตธีมเมื่อเปลี่ยนโหมด
    const observer = new MutationObserver(() => {
        downtimeChart.destroy();
        downtimeChart = createDowntimeChart(machines, downtimeDurations);
    });

    observer.observe(document.body, { attributes: true, attributeFilter: ['class'] });

    // ฟอร์มสำหรับดึงข้อมูลใหม่
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
                const newDowntimeDurations = data.downtimeDurations;
                downtimeDetails = data.downtimeDetails;

                downtimeChart.destroy();
                downtimeChart = createDowntimeChart(machines, newDowntimeDurations);
            })
            .catch(error => console.error('Error fetching data:', error));
    });
});