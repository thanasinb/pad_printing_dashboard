<?php
require 'pp-session-start.php'
    ?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard</title>
<link href="css/simple-datatables@latest/dist/style.css" rel="stylesheet" />
<link href="css/litepicker/dist/css/litepicker.css" rel="stylesheet" />
<link href="css/styles.css" rel="stylesheet" />
<link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
<script data-search-pseudo-elements defer src="js/font-awesome/5.15.3/js/all.min.js"></script>
<script src="js/feather-icons/4.28.0/feather.min.js"></script>
<link rel="stylesheet" href="css/majorette.css">
    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/jquery/jquery-ui.min.js"></script>
    <!--- Activities library -->
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
        width: 100%;
        height: 20rem;
    }
        #chartjs-tooltip {
        max-height: 150px;
        overflow-y: auto;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        border-radius: 3px;
        padding: 10px;
        pointer-events: none;
        position: absolute;
        transform: translate(-50%, 0);
        transition: all .1s ease;
        z-index: 9999;
    }
    </style>
</head>
<body class="nav-fixed">
<?php require 'pp-machine-sidenavAccordion.php'; ?>
<div id="layoutSidenav">
    <?php require 'pp-layoutSidenav_nav.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
                <div class="container-xl px-4">
                    <div class="page-header-content pt-4">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto mt-4">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i data-feather="activity"></i></div>
                                    Dashboard
                                </h1>
                                <div class="page-header-subtitle">Example dashboard overview and content summary</div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Main page content-->
            <div class="container-xl px-4 mt-n10">
                <div class="row">
                    <div class="container-xxl-8">
                        <!-- Tabbed dashboard card example-->
                        <div class="card mb-4">
                            <div class="card-header border-bottom">
                                <!-- Dashboard card navigation-->
                                <ul class="nav nav-tabs card-header-tabs" id="dashboardNav" role="tablist">
                                    <li class="nav-item me-1">
                                        <a class="nav-link active" id="overview-pill" href="#overview" data-bs-toggle="tab" role="tab" aria-controls="overview" aria-selected="true">Overview</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="dashboardNavContent">
                                    <!-- Dashboard Tab Pane 1-->
                                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-pill">
                                        <div class="chart-container">
                                            <canvas id="machineChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer class="footer-admin mt-auto footer-light">
            <div class="container-xl px-4">
                <div class="row">
                    <div class="col-md-6 small">Copyright &copy; Your Website 2021</div>
                    <div class="col-md-6 text-md-end small">
                        <a href="#!">Privacy Policy</a>
                        &middot;
                        <a href="#!">Terms &amp; Conditions</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="/projects/mjrqr/js/scripts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
<script src="/projects/mjrqr/js/datatables/datatables-simple-demo.js"></script>
<script>
    <?php require 'pp-mc-get-to-chart.php'; ?>
    document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('machineChart').getContext('2d');

    // ข้อมูลจาก PHP
    const machines = <?php echo $machines_json; ?>;
    const jobCounts = <?php echo $jobCounts_json; ?>;
    const taskDetails = <?php echo $task_details_json; ?>;

    console.log('Machines:', machines);
    console.log('Job Counts:', jobCounts);
    console.log('Task Details:', taskDetails);

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
}
}
},
    plugins: {
    title: {
    display: true,
    text: 'Machine Statistics'
},
    tooltip: {
    enabled: false,
    external: function(context) {
    // Tooltip Element
    let tooltipEl = document.getElementById('chartjs-tooltip');
    if (!tooltipEl) {
    tooltipEl = document.createElement('div');
    tooltipEl.id = 'chartjs-tooltip';
    tooltipEl.classList.add('chartjs-tooltip');
    document.body.appendChild(tooltipEl);
}

    // Hide if no tooltip
    const tooltipModel = context.tooltip;
    if (tooltipModel.opacity === 0) {
    tooltipEl.style.opacity = 0;
    return;
}

    // Set caret Position
    tooltipEl.classList.remove('above', 'below', 'no-transform');
    if (tooltipModel.yAlign) {
    tooltipEl.classList.add(tooltipModel.yAlign);
} else {
    tooltipEl.classList.add('no-transform');
}

    function getBody(bodyItem) {
    return bodyItem.lines;
}

    // Set Text
    if (tooltipModel.body) {
    const index = context.tooltip.dataPoints[0].dataIndex;
    const taskDetail = taskDetails[index].map(task => `${task}`).join('<br>');
    const titleLines = tooltipModel.title || [];
    const bodyLines = tooltipModel.body.map(getBody);

    let innerHtml = '<thead>';

    titleLines.forEach(function(title) {
    innerHtml += '<tr><th>' + title + '</th></tr>';
});

    innerHtml += '</thead><tbody>';

    bodyLines.forEach(function(body, i) {
    const colors = tooltipModel.labelColors[i];
    let style = 'background:' + colors.backgroundColor;
    style += '; border-color:' + colors.borderColor;
    style += '; border-width: 2px';
    const span = '<span style="' + style + '"></span>';
    innerHtml += '<tr><td>' + span + body + '</td></tr>';
});

    innerHtml += '<tr><td>ID Tasks:<br>' + taskDetail + '</td></tr>';
    innerHtml += '</tbody>';

    let tableRoot = tooltipEl.querySelector('table');
    if (!tableRoot) {
    tableRoot = document.createElement('table');
    tooltipEl.appendChild(tableRoot);
}
    tableRoot.innerHTML = innerHtml;
}

    const position = context.chart.canvas.getBoundingClientRect();
    tooltipEl.style.opacity = 1;
    tooltipEl.style.left = position.left + window.pageXOffset + tooltipModel.caretX + 'px';
    tooltipEl.style.top = position.top + window.pageYOffset + tooltipModel.caretY + 'px';
    tooltipEl.style.font = tooltipModel.options.bodyFont.string;
    tooltipEl.style.padding = tooltipModel.options.padding + 'px ' + tooltipModel.options.padding + 'px';
}
}
}
}
});
});
</script>
</body>
</html>

