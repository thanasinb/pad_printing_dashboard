$(document).ready(function(){

    var setting_dt_modal = document.getElementById('setting_dt_modal');

    setting_dt_modal.addEventListener('show.bs.modal', function (event) {
        // Button that triggered the modal
        var button = event.relatedTarget;
        // Extract info from data-bs-* attributes
        // var id_machine = button.getAttribute('data-bs-id_machine');

        // id_machine = button.getAttribute('data-bs-id_machine');
        // item_no = button.getAttribute('data-bs-item_no');
        // operation = button.getAttribute('data-bs-operation');
        // date_due = button.getAttribute('data-bs-date_due');
        // alert($(event.relatedTarget).parent().parent().find('.qty_per_tray').html());

        // id_machine = $(event.relatedTarget).parent().parent().find('.id_machine').text();
        // item_no = $(event.relatedTarget).parent().parent().find('.item_no').text();
        // operation = $(event.relatedTarget).parent().parent().find('.operation').text();
        // date_due = $(event.relatedTarget).parent().parent().find('.date_due').text();
        // qty_per_tray = $(event.relatedTarget).parent().parent().find('.qty_per_tray').text();
        // qty_accum = button.getAttribute('data-bs-qty_accum');
        // qty_order = button.getAttribute('data-bs-qty_order');
        // qty_percent = button.getAttribute('data-bs-qty_percent');
        // id_task = button.getAttribute('data-bs-id_task');
        // id_job = button.getAttribute('data-bs-id_job');
        // last_update = button.getAttribute('data-bs-last_update');

        // If necessary, you could initiate an AJAX request here
        // and then do the updating in a callback.
        //
        // Update the modal's content.
        // var modalTitle = currentTaskModal.querySelector('.modal-title');
        // var modal_id_machine = currentTaskModal.querySelector('#modal_id_machine');
        // var modal_item_no = currentTaskModal.querySelector('#modal_item_no');
        // var modal_operation = currentTaskModal.querySelector('#modal_operation');
        // var modal_date_due = currentTaskModal.querySelector('#modal_date_due');
        // var modal_qty_per_tray = currentTaskModal.querySelector('#modal_qty_per_tray');
        // var modal_qty_accum = currentTaskModal.querySelector('#modal_qty_accum');
        // var modal_qty_order = currentTaskModal.querySelector('#modal_qty_order');
        // var modal_qty_percent = currentTaskModal.querySelector('#modal_qty_percent');
        // var modal_id_task = currentTaskModal.querySelector('#modal_id_task');
        // var modal_id_job = currentTaskModal.querySelector('#modal_id_job');
        // var modal_last_update = currentTaskModal.querySelector('#modal_last_update');
        //
        // modalTitle.textContent = 'Current task for machine: ' + id_machine;
        // modal_id_machine.textContent = id_machine;

    });

    // setting_dt_modal.addEventListener('hide.bs.modal', function (event) {
    //     $('input[name=radioCurrentTask]:checked').prop('checked', false);
    //     $('#modal_button_save').hide();
    // });

    // $('#setting_dt_modal').on('hide.bs.modal',function(){
    //     alert("setting_dt_modal");
    // });

    // $('body').on('click', '.downtime_edit', function(event){
    //     // alert("downtime_edit");
    // });

});
