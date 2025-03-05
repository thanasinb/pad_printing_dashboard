document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('mcovertimeChart').getContext('2d');
    let machineOvertimeChart;

    function getThemeColors() {
        const isDarkMode = document.body.classList.contains('dark-mode');
        return {
            textColor: isDarkMode ? 'white' : 'black',
            gridColor: isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'
        };
    }

    function createChart(machines, normalCounts, overtimeCounts) {
        const themeColors = getThemeColors();
        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: machines,
                datasets: [
                    {
                        label: 'Normal',
                        data: normalCounts,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Overtime',
                        data: overtimeCounts,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: true,
                        title: { display: true, text: 'Machines', color: themeColors.textColor },
                        ticks: { color: themeColors.textColor, autoSkip: false, maxRotation: 90, minRotation: 90 },
                        grid: { color: themeColors.gridColor }
                    },
                    y: {
                        stacked: true,
                        title: { display: true, text: 'Status Count', color: themeColors.textColor },
                        ticks: { color: themeColors.textColor },
                        grid: { color: themeColors.gridColor }
                    }
                },
                plugins: {
                    legend: { labels: { color: themeColors.textColor } }
                }
            }
        });
    }

    function fetchChartData(startDate = null, endDate = null) {
        let url = 'get_data_machine_overtime.php';
        if (startDate && endDate) {
            url += `?startDate=${startDate}&endDate=${endDate}`;
        }
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

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                if (machineOvertimeChart) machineOvertimeChart.destroy();
                machineOvertimeChart = createChart(data.machines, data.normalCounts, data.overtimeCounts);
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                alert('Error fetching data. Please check the console.');
            });
    }

    document.getElementById('mcovertimeDateForm').addEventListener('submit', function (event) {
        event.preventDefault();
        const startDate = document.getElementById('mcovertime_start_date').value;
        const endDate = document.getElementById('mcovertime_end_date').value;
        fetchChartData(startDate, endDate);
    });

    fetchChartData(); // โหลดข้อมูลทั้งหมดตอนเริ่มต้น
});
