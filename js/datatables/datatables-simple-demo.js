window.addEventListener('DOMContentLoaded', event => {
    // Simple-DataTables
    // https://github.com/fiduswriter/Simple-DataTables/wiki

    const datatablesSimple = document.getElementById('datatablesSimple');
    if (datatablesSimple) {
        new simpleDatatables.DataTable(datatablesSimple,{
            searchable: false,
            paging: false,
            fixedHeight: false,
            sortable: false});
    }
    var height_first_row = $('.first-row').height()+2;
    $("thead tr.second-row th, thead tr.second-row td").css("top", height_first_row)
});


$(document).ready(function(){
    $('#sidebarToggle').click(function (){
        var height_first_row = $('.first-row').height()+2;
        $("thead tr.second-row th, thead tr.second-row td").css("top", height_first_row)
    });
});