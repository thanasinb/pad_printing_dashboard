$(document).ready(function(){

    var id_machine, item_no, operation, date_due, qty_per_tray, qty_accum, qty_order, qty_percent, id_task, id_job, last_update;
    $('#modal_button_save').hide();
    $('.radioCurrentTask').click(function (){
        $('#modal_button_go').attr('disabled', false);
    });

    $('.radioNextTask').click(function (){
        $('#modal_next_button_go').attr('disabled', false);
    });

    $('.form-check-label').click(function (){
        if(!$(this).prev().prop('disabled'))
            $(this).prev().attr('checked', true);
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
        // else if (radio_checked==2){
        //     $.ajax({
        //         url: "ajax/pp-machine-force-stop.php",
        //         type: "GET",
        //         data: {
        //             id_mc: id_machine
        //         },
        //         context: this,
        //         cache: false,
        //         success: function(dataResult){
        //             // var dataResult = JSON.parse(dataResult);
        //             // alert(JSON.stringify(dataResult));
        //             $('#currentTaskModal').modal('hide');
        //         }
        //     });
        // }
        else if (radio_checked==3){
            $('#form_modal_current_task').attr('action', 'pp-machine.php');
        }
        else if (radio_checked==4){
            $('#form_modal_current_task').attr('action', 'pp-machine.php');
        }
        else if (radio_checked==5){
            $('#form_modal_current_task').attr('action', 'pp-machine.php');
        }
        else if (radio_checked==6){
            $('#form_modal_current_task').attr('action', 'pp-machine-list-task.php');
        }
        else if (radio_checked==7){
            $('#form_modal_current_task').attr('action', './update/touch-reset.php?dashboard=1&id_mc='+id_machine);
        }
        // alert(radio_checked);
        if (radio_checked!=2) {
            $('#form_modal_current_task').submit();
        }
    });

    $('#modal_next_button_go').click(function (){
        var radio_checked = $("input[name='radioNextTask']:checked").val();
        $('#next_selected_radio').val(radio_checked);
        $('#next_hidden_id_job').val(id_job);
        $('#next_hidden_id_machine').val(id_machine);
        $('#next_hidden_item_no').val(item_no);
        $('#next_hidden_operation').val(operation);

        if (radio_checked==1){
            $('#form_modal_next_task').attr('action', 'pp-machine-list-task.php');
        }else if (radio_checked==3){
            $('#form_modal_next_task').attr('action', 'pp-machine.php');
        }else if (radio_checked==4){
            $('#form_modal_next_task').attr('action', 'pp-machine.php');
        }else if (radio_checked==5){
            $('#form_modal_next_task').attr('action', 'pp-machine.php');
        }else if (radio_checked==6){
            $('#form_modal_next_task').attr('action', 'pp-machine-list-task.php');
        }
        $('#form_modal_next_task').submit();
    });

    var currentTaskModal = document.getElementById('currentTaskModal');

    currentTaskModal.addEventListener('hide.bs.modal', function (event) {
        $('input[name=radioCurrentTask]:checked').prop('checked', false);
        $('#modal_button_save').hide();
    });

    // currentTaskModal.addEventListener('hidden.bs.modal', function (event) {
    //     $('#datatablesSimple').focus();
    // });

    currentTaskModal.addEventListener('show.bs.modal', function (event) {
        // Button that triggered the modal
        var button = event.relatedTarget;
        // Extract info from data-bs-* attributes
        // var id_machine = button.getAttribute('data-bs-id_machine');

        id_machine = button.getAttribute('data-bs-id_machine');
        item_no = button.getAttribute('data-bs-item_no');
        operation = button.getAttribute('data-bs-operation');
        date_due = button.getAttribute('data-bs-date_due');
        qty_per_tray = button.getAttribute('data-bs-qty_per_tray');
        qty_accum = button.getAttribute('data-bs-qty_accum');
        qty_order = button.getAttribute('data-bs-qty_order');
        qty_percent = button.getAttribute('data-bs-qty_percent');
        id_task = button.getAttribute('data-bs-id_task');
        id_job = button.getAttribute('data-bs-id_job');
        last_update = button.getAttribute('data-bs-last_update');

        // If necessary, you could initiate an AJAX request here
        // and then do the updating in a callback.
        //
        // Update the modal's content.
        var modalTitle = currentTaskModal.querySelector('.modal-title');
        var modal_id_machine = currentTaskModal.querySelector('#modal_id_machine');
        var modal_item_no = currentTaskModal.querySelector('#modal_item_no');
        var modal_operation = currentTaskModal.querySelector('#modal_operation');
        var modal_date_due = currentTaskModal.querySelector('#modal_date_due');
        var modal_qty_per_tray = currentTaskModal.querySelector('#modal_qty_per_tray');
        var modal_qty_accum = currentTaskModal.querySelector('#modal_qty_accum');
        var modal_qty_order = currentTaskModal.querySelector('#modal_qty_order');
        var modal_qty_percent = currentTaskModal.querySelector('#modal_qty_percent');
        var modal_id_task = currentTaskModal.querySelector('#modal_id_task');
        var modal_id_job = currentTaskModal.querySelector('#modal_id_job');
        var modal_last_update = currentTaskModal.querySelector('#modal_last_update');

        modalTitle.textContent = 'Current task for machine: ' + id_machine;
        modal_id_machine.textContent = id_machine;
        if (item_no!='') {
            $('#radioChangeOp').attr('disabled', false);
            // $('#radioForceStop').attr('disabled', false);
            $('#radioComplete').attr('disabled', false);
            $('#radioRemove').attr('disabled', false);
            $('#radioNextQueue').attr('disabled', true);
            $('#radioNewTask').attr('disabled', true);
            $('#modal_button_go').attr('disabled', true);

            modal_item_no.textContent = item_no;
            modal_operation.textContent = operation;
            modal_date_due.textContent = date_due;
            $('#modal_qty_per_tray').val(qty_per_tray);
            modal_qty_accum.textContent = qty_accum;
            modal_qty_order.textContent = qty_order;
            modal_qty_percent.textContent = qty_percent;
            modal_id_task.textContent = id_task;
            modal_id_job.textContent = id_job;
            modal_last_update.textContent = last_update;
            // alert('Item no: ' + item_no);
        }
        else {
            // alert($('#radioChangeOp').parent().html());
            $('#radioChangeOp').attr('disabled', true);
            // $('#radioForceStop').attr('disabled', true);
            $('#radioComplete').attr('disabled', true);
            $('#radioRemove').attr('disabled', true);
            $('#radioNextQueue').attr('disabled', false);
            $('#radioNewTask').attr('disabled', false);
            $('#modal_button_go').attr('disabled', true);

            modal_item_no.textContent = '';
            modal_operation.textContent = '';
            modal_date_due.textContent = '';
            $('#modal_qty_per_tray').val(0);
            modal_qty_accum.textContent = '';
            modal_qty_order.textContent = '';
            modal_qty_percent.textContent = '';
            modal_id_task.textContent = '';
            modal_id_job.textContent = '';
            modal_last_update.textContent = '';
        }
    });

    var nextTaskModal = document.getElementById('nextTaskModal');

    nextTaskModal.addEventListener('hide.bs.modal', function (event) {
        $('input[name=radioNextTask]:checked').prop('checked', false);
    });

    nextTaskModal.addEventListener('show.bs.modal', function (event) {
        // Button that triggered the modal
        var button = event.relatedTarget;
        // Extract info from data-bs-* attributes
        // var id_machine = button.getAttribute('data-bs-id_machine');

        id_machine = button.getAttribute('data-bs-id_machine');
        item_no = button.getAttribute('data-bs-item_no');
        operation = button.getAttribute('data-bs-operation');
        date_due = button.getAttribute('data-bs-date_due');
        qty_per_tray = button.getAttribute('data-bs-qty_per_tray');
        qty_accum = button.getAttribute('data-bs-qty_accum');
        qty_order = button.getAttribute('data-bs-qty_order');
        qty_percent = button.getAttribute('data-bs-qty_percent');
        id_task = button.getAttribute('data-bs-id_task');
        id_job = button.getAttribute('data-bs-id_job');
        last_update = button.getAttribute('data-bs-last_update');

        // If necessary, you could initiate an AJAX request here
        // and then do the updating in a callback.
        //
        // Update the modal's content.
        var modalTitle = nextTaskModal.querySelector('.modal-title');
        var modal_id_machine = nextTaskModal.querySelector('#modal_next_id_machine');
        var modal_item_no = nextTaskModal.querySelector('#modal_next_item_no');
        var modal_operation = nextTaskModal.querySelector('#modal_next_operation');
        var modal_date_due = nextTaskModal.querySelector('#modal_next_date_due');
        var modal_qty_per_tray = nextTaskModal.querySelector('#modal_next_qty_per_tray');
        var modal_qty_accum = nextTaskModal.querySelector('#modal_next_qty_accum');
        var modal_qty_order = nextTaskModal.querySelector('#modal_next_qty_order');
        var modal_qty_percent = nextTaskModal.querySelector('#modal_next_qty_percent');
        var modal_id_task = nextTaskModal.querySelector('#modal_next_id_task');
        var modal_id_job = nextTaskModal.querySelector('#modal_next_id_job');
        var modal_last_update = nextTaskModal.querySelector('#modal_next_last_update');

        $('input[name="radioNextTask"]').attr('checked', false);

        modalTitle.textContent = 'Next task for machine: ' + id_machine;
        modal_id_machine.textContent = id_machine;
        if (item_no!='') {
            $('#radioNextChangeOp').attr('disabled', false);
            $('#radioNextRemove').attr('disabled', false);
            $('#radioNextNewTask').attr('disabled', true);
            $('#modal_next_button_go').attr('disabled', true);

            modal_item_no.textContent = item_no;
            modal_operation.textContent = operation;
            modal_date_due.textContent = date_due;
            modal_qty_per_tray.textContent = qty_per_tray;
            modal_qty_accum.textContent = qty_accum;
            modal_qty_order.textContent = qty_order;
            modal_qty_percent.textContent = qty_percent;
            modal_id_task.textContent = id_task;
            modal_id_job.textContent = id_job;
            modal_last_update.textContent = last_update;
        }
        else {
            $('#radioNextChangeOp').attr('disabled', true);
            $('#radioNextRemove').attr('disabled', true);
            $('#radioNextNewTask').attr('disabled', false);
            $('#modal_next_button_go').attr('disabled', true);

            modal_item_no.textContent = '';
            modal_operation.textContent = '';
            modal_date_due.textContent = '';
            modal_qty_per_tray.textContent = '';
            modal_qty_accum.textContent = '';
            modal_qty_order.textContent = '';
            modal_qty_percent.textContent = '';
            modal_id_task.textContent = '';
            modal_id_job.textContent = '';
            modal_last_update.textContent = '';
        }
    });

    $('#modal_button_change').click(function (){
        $('#modal_qty_per_tray').prop('disabled', false);
        $('#modal_button_change').hide();
        $('#modal_button_save').show();
    });

    $('#modal_button_save').click(function (){
        $('#modal_qty_per_tray').prop('disabled', true);
        $('#modal_button_save').hide();
        $('#modal_button_change').show();

        $.ajax({
            url: "ajax/pp-machine-change-tray.php",
            type: "GET",
            data: {
                qty_per_tray: qty_per_tray,
                id_task: id_task
            },
            context: this,
            cache: false,
            success: function(dataResult){
                // alert(dataResult);
                var dataResult = JSON.parse(dataResult);
                // $('#modal_staff_id').text(dataResult.id_staff);
                // $('#input_rfid').val(dataResult.id_rfid);
                // alert($(this).html());
                // document.getElementById("button_save_rfid").textContent = "hello";
                // $(this).parent().find('#button_save_rfid').hide();
                // $(this).parent().find('#button_rfid').show();
                $('#input_staff_id').text(id_staff);
                $('#input_staff_id').prop('disabled', true);
                $('#input_rfid').val(id_rfid);
                $('#input_rfid').prop('disabled', true);
                $('#prefix_name').val(prefix);
                $('#prefix_name').prop('disabled', true);
                $('#input_name').text(name);
                $('#input_name').prop('disabled', true);
                $('#input_last').text(last);
                $('#input_last').prop('disabled', true);
                $('#role').val(roles);
                $('#role').prop('disabled', true);
                $('#shift').val(shift);
                $('#shift').prop('disabled', true);
                $('#input_site').val(site);
                $('#input_site').prop('disabled', true);
                // $('.id_staff:contains(' + id_staff + ')').next('.rfid').text(id_rfid);
                // $('.id_staff:contains(' + id_staff + ')').next('.name_first').text(name);
                // $('.id_staff:contains(' + id_staff + ')').next('.name_last').text(last);
                // $('.id_staff:contains(' + id_staff + ')').next('.prefix').text(prefix);
                // $('.id_staff:contains(' + id_staff + ')').next('.role').text(roles);
                // $('.id_staff:contains(' + id_staff + ')').next('.shift').text(shift);
                $('#button_save_rfid').hide();
                $('#button_rfid').show();
            }
        });
    });
});