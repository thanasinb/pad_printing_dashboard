var frequency = 5000; // 5 seconds in miliseconds
var interval_update = 0;
var sort_key='id_mc';
var sort_dir=1; // 0 = high to low, 1 = low to high

$(document).ready(function(){
    loadData();
    startLoop();

    $('#dash_machine').click(function () {
        sort_key='id_mc';
        sort_dir=1;
    });
    $('#dash_percent').click(function () {
        sort_key='percent';
        sort_dir=0;
    });
    $('#dash_open').click(function () {
        sort_key='est_sec';
        sort_dir=1;
    });

    var currentTaskModal = document.getElementById('currentTaskModal');

    // currentTaskModal.addEventListener('hide.bs.modal', function (event) {
    //     $('input[name=radioCurrentTask]:checked').prop('checked', false);
    //     $('#modal_button_save').hide();
    //     $('#modal_button_change').show();
    //     $('#modal_qty_per_tray').attr('disabled', false);
    // });

    currentTaskModal.addEventListener('show.bs.modal', function (event) {
        var id_machine = $(event.relatedTarget).parent().parent().find('.id_machine').text();
        var modalTitle = currentTaskModal.querySelector('.modal-title');

        modalTitle.textContent = 'Current task for machine: ' + id_machine;
        // modal_id_machine.textContent = id_machine;
    });
});

// STARTS and Resets the loop
function startLoop() {
    if (interval_update > 0) clearInterval(interval_update); // stop
    interval_update = setInterval("loadData()", frequency); // run
}

function sort_by_key(array, key, dir)
{
    if(dir==1){
        return array.sort(function(a, b)
        {
            var x = a[key]; var y = b[key];
            return ((x < y) ? -1 : ((x > y) ? 1 : 0));
        });
    }else if(dir==0){
        return array.sort(function(a, b)
        {
            var x = a[key]; var y = b[key];
            return ((x > y) ? -1 : ((x < y) ? 1 : 0));
        });
    }
}

function loadData() {
    $.ajax({
        url: "ajax/pp-machine-refresh-3.php",
        type: "GET",
        context: this,
        cache: false,
        success: function(dataResult){
            var data = JSON.parse(dataResult);
            data = sort_by_key(data, sort_key, sort_dir);
            $("#table_body tr").remove();
            $.each(data, function(i, item) {
                if(item.item_no==null){
                    if(!$('#checkbox_hide_unassigned_machines').is(":checked")){
                        var row = "<tr class=\"text-black fw-bold\"><td></td>" +
                            "<td class='id_machine'>" + item.id_mc + "</td>" +
                            "<td><button name=\"id_mc\" type=\"submit\" value=\"" + item.id_mc +
                            "\" data-bs-toggle=\"modal\" data-bs-target=\"#currentTaskModal\"" +
                            "class=\"btn btn-datatable btn-icon text-black me-2 btn-current-task\">&#9997;</button></td>" +
                            "<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>&#9997;</td><td></td></tr>";
                    }
                }
                else {
                    var row;
                    row = "<tr class=\"text-black fw-bold\"></td>";
                    if (item.status_work==0){
                        row = row + "<td class=\"bg-blue\"></td>";
                    }else if(item.status_work==1){
                        if(item.rework=='y'){
                            row = row + "<td style=\"color: white\" class=\"bg-green\">" + item.id_staff + " R/W</td>";
                        }else{
                            row = row + "<td style=\"color: white\" class=\"bg-green\">" + item.id_staff + "</td>";
                        }
                    }else if(item.status_work==2){
                        row = row + "<td style=\"color: white\"  class=\"bg-yellow\">" + item.id_staff + "</td>";
                    }else if(item.status_work==-1){
                        row = row + "<td style=\"color: white\"  class=\"bg-red\">" + item.id_staff + "<br>" + item.code_downtime + "</td>";
                    }
                    row = row + "<td>" + item.id_mc + "</td>" +
                        "<td class=\"text-nowrap\">&#9997;" + item.item_no + "</td>" +
                        "<td>" + item.operation + "</td>" +
                        "<td>" + item.op_color + "/" + item.op_side + "</td>" +
                        "<td>" + item.date_due + "</td>" +
                        "<td>" + item.qty_per_tray + "</td>" +
                        "<td>" + item.qty_accum.toLocaleString('en-US') + "/" + item.qty_order.toLocaleString('en-US') + "</td>" +
                        "<td><div class=\"progress\">";
                    if (item.qty_order - item.qty_accum <= 500) {
                        row = row + "<div id=\"progress-bar\" class=\"progress-bar blink_me bg-orange\"";
                    }else {
                        row = row + "<div id=\"progress-bar\" class=\"progress-bar\"";
                    }
                    row = row + "role=\"progressbar\" style=\"width: " + item.percent + "%\" aria-valuenow=\"" +
                        item.percent + "\" aria-valuemin=\"0\" aria-valuemax=\"100\">" +
                        item.percent + "%</div></div></td>";
                    if(item.run_time_actual>item.run_time_std) {
                        row = row + "<td style=\"color: red\"><p class=\"blink_me\">&#9873; " +
                            parseFloat(item.run_time_actual).toFixed(2) + "/" + item.run_time_std + "</p></td>";
                    }else {
                        row = row + "<td><p>" + parseFloat(item.run_time_actual).toFixed(2) + "/" + item.run_time_std + "</p></td>";
                    }
                    row = row + "<td>" + item.run_time_open + "</td>";
                    // row = row + "<td>" + item.est_time + "</td>";
                    row = row + "<td>&#9997;</td><td></td></tr>";
                }
                $('#table_body').append(row);
            });
        }
    });

}