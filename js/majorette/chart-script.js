document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('machineChart').getContext('2d');

    const machines = JSON.parse(document.getElementById('machines').value);
    const jobCounts = JSON.parse(document.getElementById('jobCounts').value);
    const taskDetails = JSON.parse(document.getElementById('taskDetails').value);

    function createChart(machines, jobCounts, taskDetails) {
        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: machines,
                datasets: [{
                    label: 'Total of Jobs',
                    data: jobCounts,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',  // เปลี่ยนสีพื้นหลัง
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
                            text: 'Total of Jobs'
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

    // Create chart with initial data
    const machineChart = createChart(machines, jobCounts, taskDetails);

    // Handle form submission for loading new data
    document.getElementById('dateForm').addEventListener('submit', function (event) {
        event.preventDefault();
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;

        // Fetch new data and update the chart
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

                // Update chart with new data
                machineChart.data.labels = newMachines;
                machineChart.data.datasets[0].data = newJobCounts;
                machineChart.update();
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                alert('Error fetching data. Please check the console for more details.');
            });
    });

});
