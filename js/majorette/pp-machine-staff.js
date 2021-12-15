$(document).ready(function(){
    $('#button_save_rfid').hide();
    $('#staff_modal').on('hide.bs.modal',function(){
        $('#input_staff_id').prop('disabled', true);
        $('#input_rfid').prop('disabled', true);
        $('#prefix_name').prop('disabled', true);
        $('#input_name').prop('disabled', true);
        $('#input_last').prop('disabled', true);
        $('#shift').prop('disabled', true);
        $('#role').prop('disabled', true);
        $('#button_save_rfid').hide();
        $('#button_rfid').show();
    });

    $('#button_save_rfid').click(function () {
        // alert($('#input_rfid').val() + $('#modal_staff_id').text());
        var id_staff = $('#input_staff_id').val();
        var id_rfid = $('#input_rfid').val();
        // var prefix= $('#prefix_name').val();
        var name= $('#input_name').val();
        var last= $('#input_last').val();
        // var role= $('#role').val();
        // var shif= $('#shift').val();
        // alert(id_staff+id_rfid+name+last);
        $.ajax({
            url: "ajax/pp-staff-change-rfid.php",
            type: "GET",
            data: {
                id_staff: id_staff,
                id_rfid: id_rfid,
                name_first: name,
                name_last: last
                // prefix: prefix,
                // id_role: role,
                // id_shif: shif
            },
            context: this,
            cache: false,
            success: function(dataResult){
                // alert(dataResult);
                var dataResult = JSON.parse(dataResult);
                // alert(dataResult.statusCode);
                // $('#modal_staff_id').text(dataResult.id_staff);
                // $('#input_rfid').val(dataResult.id_rfid);
                // alert($(this).html());
                // document.getElementById("button_save_rfid").textContent = "hello";
                // $(this).parent().find('#button_save_rfid').hide();
                // $(this).parent().find('#button_rfid').show();
                // $('#input_staff_id').text(id_staff);
                // $('#input_staff_id').prop('disabled', true);
                // $('#input_rfid').val(id_rfid);
                // $('#input_rfid').prop('disabled', true);
                // $('#prefix_name').val(prefix);
                // $('#prefix_name').prop('disabled', true);
                // $('#input_name').text(name);
                // $('#input_name').prop('disabled', true);
                // $('#input_last').text(last);
                // $('#input_last').prop('disabled', true);
                // $('#role').val(role);
                // $('#role').prop('disabled', true);
                // $('#shift').val(shif);
                // $('#shift').prop('disabled', true);
                $('.id_staff:contains(' + id_staff + ')').next('.rfid').text(id_rfid);
                $('#button_save_rfid').hide();
                $('#button_rfid').show();
            }
        });

    });

    $('.staff_edit').click(function (){
        // alert('edit');
        // alert($(this).parent().find('.id_staff').text());
        // alert($(this).parent().parent().find('.id_staff').html());

        var id_staff = $(this).parent().parent().find('.id_staff').html();
        var id_rfid = $(this).parent().parent().find('.rfid').html();
        var prefix = $(this).parent().parent().find('.prefix').html();
        var name_first = $(this).parent().parent().find('.name_first').html();
        var name_last = $(this).parent().parent().find('.name_last').html();
        var roles = $(this).parent().parent().find('.role').html();
        var shift = $(this).parent().parent().find('.shift').html();

        $('#input_staff_id').val(id_staff);
        $('#input_rfid').val(id_rfid);
        $('#prefix_name  [value='+ prefix +']').attr('selected', true);
        $('#input_name').val(name_first);
        $('#input_last').val(name_last);
        $('#role  [value='+ roles +']').attr('selected', true);
        $('#shift  [value='+ shift +']').attr('selected', true);


        $('#staff_modal').modal('show');
        // $('#modal_span_staff_id').text(id_staff);
        //$.ajax({
           // url: "ajax/pp-staff-load.php",
            //type: "GET",
            //data: {
               // id_staff: id_staff
           // },
            //context: this,
            //cache: false,
            //success: function(dataResult){
                // alert(dataResult);
               // var dataResult = JSON.parse(dataResult);
               // $('#input_staff_id').text(dataResult.id_staff);
                //$('#input_rfid').val(dataResult.id_rfid);
                //$('#modal_prefix').text(dataResult.prefix);
               // $('#input_name').text(dataResult.name_first);
                //$('#input_last').text(dataResult.name_last);
                //$('#modal_site').text(dataResult.site);
               // $('#modal_role').text(dataResult.role);
                //$('#modal_shif').text(dataResult.id_shif);
           // }
        //});
    });

    $('.staff_delete').click(function (){
        var id_staff = $(this).parent().parent().find('.id_staff').html();
        $.ajax({
            url: "ajax/pp-staff-delete.php",
            type: "GET",
            data: {
                id_staff  : id_staff
            },
            context: this,
            cache: false,
            success: function(dataResult){
                var dataResult = JSON.parse(dataResult);
                $('.row_staff:contains(' + id_staff + ')').remove();
            }
        });
    });

    $('#button_rfid').click(function (){
        $('#input_staff_id').prop('disabled', false);
        $('#input_staff_id').focus();
        $('#input_rfid').prop('disabled', false);
        $('#input_rfid').focus();
        $('#prefix_name').prop('disabled', false);
        $('#prefix_name').focus();
        $('#input_name').prop('disabled', false);
        $('#input_name').focus();
        $('#input_last').prop('disabled', false);
        $('#input_last').focus();
        $('#role').prop('disabled', false);
        $('#role').focus();
        $('#shift').prop('disabled', false);
        $('#shift').focus();
        $('#button_rfid').hide();
        $('#button_save_rfid').show();
    });

});