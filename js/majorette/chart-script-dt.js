document.addEventListener('DOMContentLoaded', function () {
    const ctxDowntime = document.getElementById('downtimeChart').getContext('2d');

    const machines = JSON.parse(document.getElementById('machines').value);
    let downtimeDurations = JSON.parse(document.getElementById('downtimeDurations').value);
    let downtimeDetails = JSON.parse(document.getElementById('downtimeDetails').value);

    function createDowntimeChart(machines, downtimeDurations) {
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
                                detailsContent += `<strong>Downtime Code:</strong> ${detail.id_code_downtime}, <strong>Duration:</strong> ${detail.downtime_duration} hours<br>`;
                            });
                        } else {
                            detailsContent = 'No downtime details available.';
                        }

                        const modalContent = `
                            <strong>Machine:</strong> ${machine}<br>
                            <strong>Total Downtime:</strong> ${downtimeDurations[index]} hours<br>
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
                            text: 'Total Downtime (hours)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Machines'
                        },
                        ticks: {
                            autoSkip: false,
                            maxRotation: 90,
                            minRotation: 90
                        }
                    }
                }
            }
        });
    }

    // Create downtime chart with initial data
    const downtimeChart = createDowntimeChart(machines, downtimeDurations);

    document.getElementById('downtimeDateForm').addEventListener('submit', function (event) {
        event.preventDefault();
        const startDate = document.getElementById('downtime_start_date').value;
        const endDate = document.getElementById('downtime_end_date').value;

        // Fetch new data and update the chart
        fetch(`get_downtime_data.php?startDate=${startDate}&endDate=${endDate}`)
            .then(response => {
                console.log('Response status:', response.status); // Log response status
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Fetched data:', data); // Log fetched data to console
                if (data.error) {
                    alert(data.error);
                    return;
                }
                const newDowntimeDurations = data.downtimeDurations;
                const newDowntimeDetails = data.downtimeDetails;

                // Update chart with new data
                downtimeChart.data.datasets[0].data = newDowntimeDurations;
                downtimeChart.update();

                // Update downtimeDetails with new data
                downtimeDetails = newDowntimeDetails;
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                alert('Error fetching data. Please check the console for more details.');
            });
    });

    // Export downtimeChart to be used in other scripts
    // window.downtimeChart = downtimeChart;
});
