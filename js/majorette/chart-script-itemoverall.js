document.addEventListener('DOMContentLoaded', function () {
    function formatDate(dateString) {
        if (!dateString || dateString.trim() === "") return "N/A";
        const [year, month, day] = dateString.split('-');
        return `${day}/${month}/${year}`;
    }

    const ctx = document.getElementById('itemoverallChart').getContext('2d');

    let currentMachines = JSON.parse(document.getElementById('machines').value);
    let rawTotalQuantities = JSON.parse(document.getElementById('totalQuantities').value);
    let currentTotalQuantities = currentMachines.map(machine => Number(rawTotalQuantities[machine]) || 0);

    function getThemeColors() {
        const isDarkMode = document.body.classList.contains('dark-mode');
        return {
            textColor: isDarkMode ? 'white' : 'black',
            gridColor: isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'
        };
    }


    function createChart(machines, totalQuantities) {
        console.log("Creating chart with data:", machines, totalQuantities);
        const themeColors = getThemeColors();

        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: machines,
                datasets: [{
                    label: 'Total Quantity Completed',
                    data: totalQuantities,
                    backgroundColor: 'rgba(255, 206, 86, 0.5)',
                    borderColor: 'rgb(228,185,79)',
                    borderWidth: 1
                }]
            },

            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Total Quantity Completed', color: themeColors.textColor },
                        ticks: { color: themeColors.textColor },
                        grid: { color: themeColors.gridColor }
                    },
                    x: {
                        title: { display: true, text: 'Machines', color: themeColors.textColor },
                        ticks: { color: themeColors.textColor, autoSkip: false, maxRotation: 90, minRotation: 90 },
                        grid: { color: themeColors.gridColor }
                    }
                },
                onClick: function (event, elements) {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        const machine = currentMachines[index];
                        const quantity = currentTotalQuantities[index];

                        const startDateInput = document.getElementById('itemoverall_start_date').value;
                        const endDateInput = document.getElementById('itemoverall_end_date').value;

                        const startDate = startDateInput ? formatDate(startDateInput) : "N/A";
                        const endDate = endDateInput ? formatDate(endDateInput) : "N/A";


                        document.getElementById("modalContent").innerHTML = `
                            <strong>Selected Date Range:</strong> ${startDate} to ${endDate}<br>
                            <strong>Machine:</strong> ${machine}<br>
                            <strong>Total Quantity Completed:</strong> ${quantity.toLocaleString()} pieces<br>
                        `;


                        // ✅ แสดง Modal
                        let modal = new bootstrap.Modal(document.getElementById('detailModal'));
                        modal.show();
                    }
                }

            }
        });
    }

    let itemoverallChart = createChart(currentMachines, currentTotalQuantities);

    document.getElementById('itemoverallDateForm').addEventListener('submit', function (event) {
        event.preventDefault();
        const startDate = document.getElementById('itemoverall_start_date').value;
        const endDate = document.getElementById('itemoverall_end_date').value;
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

        fetch('get_data_itemoverall.php', {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ startDate, endDate })
        })
            .then(response => response.json())
            .then(data => {
                console.log("Fetched Data from PHP:", data);

                if (data.error) {
                    alert(data.error);
                    return;
                }

                currentMachines = data.machines;
                currentTotalQuantities = currentMachines.map(machine => Number(data.totalQuantities[machine]) || 0);

                if (itemoverallChart) {
                    itemoverallChart.destroy();
                }
                itemoverallChart = createChart(currentMachines, currentTotalQuantities);
            })
            .catch(error => console.error('Error fetching data:', error));
    });
});
