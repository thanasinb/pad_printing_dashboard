$(document).ready(function(){
    // WORK
    // $('.input-date-text').click(function(){
    //     alert("hello!");
    // });

    // WORK
    // $('#datetimepicker_date_0').on('change.datetimepicker', function (){
    //     alert($('#input_date_0').val());
    // });

    $('.datetimepicker_date').on('change.datetimepicker', function (){
        // alert($(this).find('input:first').val());
        // alert($(this).closest('td').prev().prev().prev().html());
        // alert($(this).closest('tr').find('.id_machine').html());

        var id_machine=$(this).closest('tr').find('.id_machine').html();
        var comp_date=$(this).find('input:first').val();
        comp_date = comp_date.substring(6,10)+'-'+comp_date.substring(3,5)+'-'+comp_date.substring(0,2);
        // alert(comp_date);
        $.ajax({
            url: "ajax/pp-machine-assign-date.php",
            type: "POST",
            data: {
                id_machine: id_machine,
                comp_date: comp_date
            },
            cache: false,
            success: function(dataResult){
                // var dataResult = JSON.parse(dataResult);
                // // alert(dataResult.sql.toString());
                // if(dataResult.statusCode==200){
                //     alert('Data added successfully !');
                // }
                // else {
                //     alert("Error occured !");
                //     // alert(dataResult.sql);
                // }
            }
        });
    });
    $('.datetimepicker_time').on('change.datetimepicker', function (){
        // alert($(this).find('input:first').val());
        // alert($(this).closest('td').prev().prev().prev().html());
        // alert($(this).closest('tr').find('.id_machine').html());

        var id_machine=$(this).closest('tr').find('.id_machine').html();
        var comp_time=$(this).find('input:first').val();
        comp_time = comp_time + ':00';
        // alert(id_machine+comp_date);
        $.ajax({
            url: "ajax/pp-machine-assign-time.php",
            type: "POST",
            data: {
                id_machine: id_machine,
                comp_time: comp_time
            },
            cache: false,
            success: function(dataResult){
                // var dataResult = JSON.parse(dataResult);
                // // alert(dataResult.sql.toString());
                // if(dataResult.statusCode==200){
                //     alert('Data added successfully !');
                // }
                // else {
                //     alert("Error occured !");
                //     // alert(dataResult.sql);
                // }
            }
        });
    });
});