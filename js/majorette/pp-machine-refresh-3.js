var frequency = 5000; // 5 seconds in miliseconds
var interval_update = 0;
var sort_key='id_mc';
var sort_dir=1; // 0 = high to low, 1 = low to high

$(document).ready(function(){
    loadData();
    startLoop();

    var id_machine, item_no, id_job, operation, id_task;

    $('#modal_button_save').hide();
    $('#modal_qty_per_tray').prop('disabled', true);
    $('.radioCurrentTask').click(function (){
        $('#modal_button_go').attr('disabled', false);
    });
    $('.radioNextTask').click(function (){
        $('#modal_next_button_go').attr('disabled', false);
    });

    $('#dash_machine').click(function () {
        sort_key='id_mc';
        sort_dir=1;
        $('#dash_percent').html("Progress (%)<br><i class=\"fas fa-arrow-down\" style=\"opacity: 0.3\"></i>");
        $('#dash_machine').html("M/C<br><i class=\"fas fa-arrow-down\" style=\"opacity: 1\"></i>");
        $('#dash_cycle').html("Cycle time<br>Tray/Shif/Std<br><i class=\"fas fa-arrow-down\" style=\"opacity: 0.3\"></i>");
        $('#dash_open').html("Open<br>run time<br><i class=\"fas fa-arrow-down\" style=\"opacity: 0.3\"></i>");
    });
    $('#dash_percent').click(function () {
        sort_key='percent';
        sort_dir=0;
        $('#dash_percent').html("Progress (%)<br><i class=\"fas fa-arrow-down\" style=\"opacity: 1\"></i>");
        $('#dash_machine').html("M/C<br><i class=\"fas fa-arrow-down\" style=\"opacity: 0.3\"></i>");
        $('#dash_cycle').html("Cycle time<br>Tray/Shif/Std<br><i class=\"fas fa-arrow-down\" style=\"opacity: 0.3\"></i>");
        $('#dash_open').html("Open<br>run time<br><i class=\"fas fa-arrow-down\" style=\"opacity: 0.3\"></i>");
    });
    $('#dash_cycle').click(function () {
        sort_key='flag_cycle_time';
        sort_dir=0;
        $('#dash_percent').html("Progress (%)<br><i class=\"fas fa-arrow-down\" style=\"opacity: 0.3\"></i>");
        $('#dash_machine').html("M/C<br><i class=\"fas fa-arrow-down\" style=\"opacity: 0.3\"></i>");
        $('#dash_cycle').html("Cycle time<br>Tray/Shif/Std<br><i class=\"fas fa-arrow-down\" style=\"opacity: 1\"></i>");
        $('#dash_open').html("Open<br>run time<br><i class=\"fas fa-arrow-down\" style=\"opacity: 0.3\"></i>");
    });
    $('#dash_open').click(function () {
        sort_key='est_sec';
        sort_dir=1;
        $('#dash_percent').html("Progress (%)<br><i class=\"fas fa-arrow-down\" style=\"opacity: 0.3\"></i>");
        $('#dash_machine').html("M/C<br><i class=\"fas fa-arrow-down\" style=\"opacity: 0.3\"></i>");
        $('#dash_cycle').html("Cycle time<br>Tray/Shif/Std<br><i class=\"fas fa-arrow-down\" style=\"opacity: 0.3\"></i>");
        $('#dash_open').html("Open<br>run time<br><i class=\"fas fa-arrow-down\" style=\"opacity: 1\"></i>");
    });

    $('#modal_button_go').click(function (){
        var radio_checked = $("input[name='radioCurrentTask']:checked").val();
        $('#selected_radio').val(radio_checked);
        $('#hidden_id_job').val(id_job);
        $('#hidden_id_machine').val(id_machine);
        $('#hidden_item_no').val(item_no);
        $('#hidden_operation').val(operation);

        if (radio_checked==1){
            $('#form_modal_current_task').attr('action', 'pp-machine-list-task.php');
        }
        else if (radio_checked==3){
            $('#form_modal_current_task').attr('action', 'pp-machine-3.php');
        }
        else if (radio_checked==4){
            $('#form_modal_current_task').attr('action', 'pp-machine-3.php');
        }
        else if (radio_checked==5){
            $('#form_modal_current_task').attr('action', 'pp-machine-3.php');
        }
        else if (radio_checked==6){
            $('#form_modal_current_task').attr('action', 'pp-machine-list-task.php');
        }
        else if (radio_checked==7){
            $('#form_modal_current_task').attr('action', './update/touch-reset.php?dashboard=1&id_mc='+id_machine);
        }
        if (radio_checked!=2) {
            $('#form_modal_current_task').submit();
        }
    });

    $('#modal_next_button_go').click(function (){
        var radio_checked = $("input[name='radioNextTask']:checked").val();
        $('#next_selected_radio').val(radio_checked);

        if (radio_checked==1){
            $('#form_modal_next_task').attr('action', 'pp-machine-list-task.php');
        }else if (radio_checked==3){
            $('#form_modal_next_task').attr('action', 'pp-machine-3.php');
        }else if (radio_checked==4){
            $('#form_modal_next_task').attr('action', 'pp-machine-3.php');
        }else if (radio_checked==5){
            $('#form_modal_next_task').attr('action', 'pp-machine-3.php');
        }else if (radio_checked==6){
            $('#form_modal_next_task').attr('action', 'pp-machine-list-task.php');
        }
        $('#form_modal_next_task').submit();
    });

    var currentTaskModal = document.getElementById('currentTaskModal');

    currentTaskModal.addEventListener('hide.bs.modal', function (event) {
        $('input[name=radioCurrentTask]:checked').prop('checked', false);
        $('#modal_button_save').hide();
        $('#modal_button_change').show();
        $('#modal_qty_per_tray').attr('disabled', true);
        $('#modal_qty_shif').attr('disabled', true);
        $('#modal_id_machine').text('');
        $('#modal_item_no').text('');
        $('#modal_operation').text('');
        $('#modal_date_due').text('');
        $('#modal_qty_per_tray').val(null);
        $('#modal_qty_shif').val(null);
        $('#modal_qty_order').text('');
        $('#modal_id_task').text('');
        $('#modal_id_job').text('');
        $('#modal_last_update').text('');
    });

    currentTaskModal.addEventListener('show.bs.modal', function (event) {
        id_machine = $(event.relatedTarget).parent().parent().find('.id_machine').text();
        item_no = $(event.relatedTarget).parent().parent().find('.item_no').text();
        var modal_id_machine = currentTaskModal.querySelector('#modal_id_machine');
        var modal_item_no = currentTaskModal.querySelector('#modal_item_no');
        var modalTitle = currentTaskModal.querySelector('.modal-title');
        modalTitle.textContent = 'Current task for machine: ' + id_machine;
        modal_id_machine.textContent = id_machine;
        modal_item_no.textContent = item_no.replace('✍','');

        if (item_no!='') {
            $('#radioChangeOp').attr('disabled', false);
            $('#radioResetActivity').attr('disabled', false);
            $('#radioComplete').attr('disabled', false);
            $('#radioRemove').attr('disabled', false);
            $('#radioNextQueue').attr('disabled', true);
            $('#radioNewTask').attr('disabled', true);
            $('#modal_button_go').attr('disabled', true);
            $('#modal_button_change').attr('disabled', false);

            $.ajax({
                url: "ajax/pp-modal-get.php",
                type: "GET",
                data: {
                    id_mc: id_machine
                },
                context: this,
                cache: false,
                success: function(dataResult){
                    var data = JSON.parse(dataResult);
                    id_job = data.id_job;
                    operation = data.operation;
                    id_task = data.id_task;
                    $('#modal_operation').text(data.operation);
                    $('#modal_date_due').text(data.date_due);
                    $('#modal_qty_per_tray').val(data.qty_per_tray);
                    $('#modal_qty_shif').val(data.qty_shif);
                    $('#modal_qty_order').text(data.qty_order);
                    $('#modal_id_task').text(data.id_task);
                    $('#modal_id_job').text(data.id_job);
                    $('#modal_last_update').text(data.last_update);
                }
            });
        }
        else{
            $('#modal_button_change').attr('disabled', true);
            $('#radioChangeOp').attr('disabled', true);
            $('#radioResetActivity').attr('disabled', true);
            $('#radioComplete').attr('disabled', true);
            $('#radioRemove').attr('disabled', true);
            $('#radioNextQueue').attr('disabled', false);
            $('#radioNewTask').attr('disabled', false);
            $('#modal_button_go').attr('disabled', true);
        }
    });

    $('#modal_button_change').click(function (){
        $('#modal_qty_per_tray').prop('disabled', false);
        $('#modal_qty_shif').prop('disabled', false);
        $('#modal_button_change').hide();
            $('#modal_button_save').show();
        });

    $('#modal_button_save').click(function (){
            var qty_per_tray = $('#modal_qty_per_tray').val();
            var qty_shif = $('#modal_qty_shif').val();

            $.ajax({
                url: "ajax/pp-machine-change-tray.php",
                type: "GET",
                data: {
                    qty_per_tray: qty_per_tray,
                    qty_shif: qty_shif,
                    id_task: id_task
                },
                context: this,
                cache: false,
                success: function(dataResult){
                    var dataResult = JSON.parse(dataResult);
                    $('.id_machine:contains(' + id_machine + ')').parent().find('.qty_per_tray').text(qty_per_tray);
                    $('#modal_qty_per_tray').prop('disabled', true);
                    $('#modal_qty_shif').prop('disabled', true);
                    $('#modal_button_save').hide();
                    $('#modal_button_change').show();
                    // console.log(dataResult.affected_rows);
                }
            });
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
                var html_btn_current_modal = "<button name=\"id_mc\" type=\"submit\" value=\"" + item.id_mc +
                                                    "\" data-bs-toggle=\"modal\" data-bs-target=\"#currentTaskModal\"" +
                                                    "class=\"btn btn-datatable btn-icon text-black me-2 btn-current-task\">&#9997;</button>"
                if(item.item_no==null){
                    if(!$('#checkbox_hide_unassigned_machines').is(":checked")){
                        var row = "<tr class=\"text-black fw-bold\"><td></td>" +
                            "<td class='id_machine'>" + item.id_mc + "</td>" +
                            "<td>" + html_btn_current_modal + "</td>" +
                            "<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>&#9997;</td><td></td></tr>";
                    }
                }
                else {
                    var row;
                    row = "<tr class=\"text-black fw-bold\"></td>";
                    if (item.status_work==0){
                        row = row + "<td class=\"bg-blue\"></td>";
                    }else if(item.status_work==1){
                        if(item.rework=='y'){
                            row = row + "<td style=\"color: white\" class=\"bg-green\">" + item.id_staff + "<br>R/W</td>";
                        }else{
                            row = row + "<td style=\"color: white\" class=\"bg-green\">" + item.id_staff + "</td>";
                        }
                    }else if(item.status_work==2){
                        row = row + "<td style=\"color: white\"  class=\"bg-yellow\">" + item.id_staff + "</td>";
                    }else if(item.status_work==-1){
                        row = row + "<td style=\"color: white\"  class=\"bg-red\">" + item.id_staff + "<br>" + item.code_downtime + "</td>";
                    }
                    row = row + "<td class='id_machine'>" + item.id_mc + "</td>" +
                        "<td class=\"text-nowrap item_no\">" + html_btn_current_modal + item.item_no + "</td>" +
                        "<td class=\"operation\">" + item.operation + "</td>" +
                        "<td>" + item.op_color + "/" + item.op_side + "</td>" +
                        "<td>" + item.date_due + "</td>" +
                        "<td>" + item.qty_per_tray + "</td>";
                    row = row + "<td>" + item.qty_shif.toLocaleString('en-US') + "</td>";
                    row = row + "<td>" + item.qty_accum.toLocaleString('en-US') + "/" + item.qty_order.toLocaleString('en-US') + "</td>" +
                        "<td><div class=\"progress\">";
                    if (item.qty_order - item.qty_accum <= 500) {
                        row = row + "<div id=\"progress-bar\" class=\"progress-bar blink_me bg-orange\"";
                    }else {
                        row = row + "<div id=\"progress-bar\" class=\"progress-bar\"";
                    }
                    row = row + "role=\"progressbar\" style=\"width: " + item.percent + "%\" aria-valuenow=\"" +
                        item.percent + "\" aria-valuemin=\"0\" aria-valuemax=\"100\">" +
                        item.percent + "%</div></div></td>";
                    var float_run_time_tray, float_run_time_actual, float_run_time_std;
                    float_run_time_tray = parseInt(parseFloat(item.run_time_tray).toFixed(2)*100);
                    float_run_time_actual = parseInt(parseFloat(item.run_time_actual).toFixed(2)*100);
                    float_run_time_std = parseInt(parseFloat(item.run_time_std).toFixed(2)*100);
                    if(float_run_time_tray>float_run_time_std || float_run_time_actual>float_run_time_std){
                        row = row + "<td><span style=\"color: red\" class='blink_me'>&#9873; </span>";
                    }else{
                        row = row + "<td>";
                    }
                    if(float_run_time_tray>float_run_time_std){
                        row = row + "<span style=\"color: red\">" + item.run_time_tray + "</span>/";
                    }else {
                        row = row + item.run_time_tray + "/";
                    }
                    if(float_run_time_actual>float_run_time_std) {
                        row = row + "<span style=\"color: red\">" + item.run_time_actual + "</span>/";
                    }else {
                        row = row + item.run_time_actual + "/";
                    }
                    row = row + item.run_time_std + "</td><td>" + item.run_time_open + "</td>";
                    // row = row + "<td>" + item.est_time + "</td>";
                    row = row + "<td>&#9997;</td><td></td></tr>";
                }
                $('#table_body').append(row);
            });
        }
    });
}