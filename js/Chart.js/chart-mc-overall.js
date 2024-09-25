<?php require 'pp-mc-get-to-chart.php'; ?>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('machineChart').getContext('2d');

    const machines = <?php echo $machines_json; ?>;
    const jobCounts = <?php echo $jobCounts_json; ?>;
    const taskDetails = <?php echo $task_details_json; ?>;

    const machineData = {
        labels: machines,
        datasets: [{
            label: 'Total of Jobs',
            data: jobCounts,
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }]
    };

    const machineChart = new Chart(ctx, {
        type: 'bar',
        data: machineData,
        options: {
            onClick: function (evt, elements) {
                if (elements.length > 0) {
                    const element = elements[0];
                    const index = element.index;
                    const machine = machines[index];
                    const jobCount = jobCounts[index];
                    const tasks = taskDetails[index].map(task => `${task}`).join('<br>');

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
});
