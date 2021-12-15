$(document).ready(function(){
    // $('.btn-machine').click(function (){
    //     var operation_new = $(this).parent().parent().find('#td_operation').html();
    //     var id_job = $(this).parent().parent().find('#td_job').html();
    //     $('#hidden_operation_new').val(operation_new);
    //     // FOR ADDING A NEW TASK
    //     if ($('#selected_radio').val()==6){
    //         $('#hidden_id_job').val(id_job);
    //     }
    //     $('#form_post').submit();
    // });
    $(document).on('click', '.btn-machine', function(){
        var operation_new = $(this).parent().parent().find('#td_operation').html();
        var id_job = $(this).parent().parent().find('#td_job').html();
        $('#hidden_operation_new').val(operation_new);
        // FOR ADDING A NEW TASK
        if ($('#selected_radio').val()==6){
            $('#hidden_id_job').val(id_job);
        }
        $('#form_post').submit();
    });
});