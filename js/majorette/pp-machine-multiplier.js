$(document).ready(function(){
    // WORK
    // $('.input-date-text').click(function(){
    //     alert("hello!");
    // });

    // WORK
    // $('#datetimepicker_date_0').on('change.datetimepicker', function (){
    //     alert($('#input_date_0').val());
    // });

    $('.multiplier').on('click', function () {
        // alert($(this).find('input:first').val());
        // alert($(this).closest('td').prev().prev().prev().html());
        // alert($(this).closest('tr').find('.id_machine').html());

        // var id_machine = $(this).closest('tr').find('.id_machine').html();
        // var comp_date = $(this).find('input:first').val();
        // comp_date = comp_date.substring(6, 10) + '-' + comp_date.substring(3, 5) + '-' + comp_date.substring(0, 2);
        // alert(comp_date);

        // alert($(this).closest('.multiplier').find('input').val());
        $(this).closest('.multiplier').find('input').removeAttr('disabled');
        $(this).closest('.multiplier').find('input').focus();
        $(this).addClass("bg-yellow-soft");
    });
    $('.multiplier').focusout(function() {
        var id_machine;
        var multiplier;

        id_machine=$(this).closest('tr').find('.id_machine').html();
        multiplier=$(this).closest('.multiplier').find('input').val();

        $(this).closest('.multiplier').find('input').attr('disabled');
        $(this).removeClass("bg-yellow-soft");
        $.ajax({
            url: "ajax/pp-machine-change-multiplier.php",
            type: "POST",
            data: {
                id_mc: id_machine,
                m: multiplier
            },
            context: this,
            cache: false,
            success: function(dataResult){
                var dataResult = JSON.parse(dataResult);
                alert(dataResult.statusCode);
            }
        });

    });
});