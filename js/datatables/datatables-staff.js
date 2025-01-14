window.addEventListener('DOMContentLoaded', event => {
    // Initialize Simple-DataTables
    const datatablesSimple = document.getElementById('datatablesSimple');
    if (datatablesSimple) {
        const dataTable = new simpleDatatables.DataTable(datatablesSimple, {
            searchable: true,
            paging: true,
            fixedHeight: true
        });

        // Add filter for each column
        const filterAction = document.getElementById('filter-action'); // Filter Dropdown for Action Column
        if (filterAction) {
            filterAction.addEventListener('change', function () {
                const filterValue = filterAction.value.toLowerCase();
                if (filterValue === '') {
                    dataTable.columns().search(4, ''); // Clear filter
                } else {
                    dataTable.columns().search(4, filterValue); // Apply filter to the 4th column (Action)
                }
            });
        }
    }

    // Adjust header row position if needed
    const heightFirstRow = $('.first-row').height() + 2;
    $("thead tr.second-row th, thead tr.second-row td").css("top", heightFirstRow);
});

$(document).ready(function () {
    $('#sidebarToggle').click(function () {
        const heightFirstRow = $('.first-row').height() + 2;
        $("thead tr.second-row th, thead tr.second-row td").css("top", heightFirstRow);
    });
});