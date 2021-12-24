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
        $('#input_site').prop('disabled', true);
        $('#button_save_rfid').hide();
        $('#button_rfid').show();
    });

    $('#button_save_rfid').click(function () {
        // alert($('#input_rfid').val() + $('#modal_staff_id').text());
        var id_staff = $('#input_staff_id').val();
        var id_rfid = $('#input_rfid').val();
        var prefix= $('#prefix_name').val();
        var name= $('#input_name').val();
        var last= $('#input_last').val();
        var roles= $('#role').val();
        var shift= $('#shift').val();
        var site= $('#input_site').val();
        // alert(id_staff+id_rfid);
        $.ajax({
            url: "ajax/pp-staff-change-rfid.php",
            type: "GET",
            data: {
                id_staff: id_staff,
                id_rfid: id_rfid,
                name_first:name,
                name_last:last,
                prefix:prefix,
                id_role:roles,
                id_shif:shift,
                site:site,
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

    $('body').on('click', '.staff_edit', function(event){
        // $('.staff_edit').click(function (){
        // alert('edit');
        // alert($(this).parent().find('.id_staff').text());
        // alert($(this).parent().parent().find('.id_staff').html());

        var id_staff = $(this).parent().parent().find('.id_staff').html();
        var id_rfid = $(this).parent().parent().find('.rfid').html();
        var prefix = $(this).parent().parent().find('.prefix').html();
        var name_first = $(this).parent().parent().find('.name_first').html();
        var name_last = $(this).parent().parent().find('.name_last').html();
        var role = $(this).parent().parent().find('.role').html();
        var shif = $(this).parent().parent().find('.shif').html();

        var prefix_val;
        if (prefix==='นาย'){
            prefix_val=1;
        }else if(prefix==='นาง'){
            prefix_val=2;
        }else if(prefix==='นางสาว'){
            prefix_val=3;
        }
        var role_val;
        if (role==='Operator'){
            role_val=1;
        }else if(prefix==='Technician'){
            role_val=2;
        }
        // alert(prefix + prefix_val + role + role_val+ shif);

        $('#input_staff_id').val(id_staff);
        $('#input_rfid').val(id_rfid);
        $('#prefix_name').val(prefix_val);
        $('#input_name').val(name_first);
        $('#input_last').val(name_last);
        $('#role').val(role_val);
        $('#shift').val(shif);

        $('#staff_modal').modal('show');
        // $('#modal_span_staff_id').text(id_staff);
        $.ajax({
         url: "ajax/pp-staff-load.php",
        type: "GET",
        data: {
         id_staff: id_staff
         },
        context: this,
        cache: false,
        success: function(dataResult){
        // alert(dataResult);
         var dataResult = JSON.parse(dataResult);
        // $('#input_staff_id').text(dataResult.id_staff);
        //$('#input_rfid').val(dataResult.id_rfid);
        //$('#modal_prefix').text(dataResult.prefix);
        // $('#input_name').text(dataResult.name_first);
        //$('#input_last').text(dataResult.name_last);
        $('#input_site').val(dataResult.site);
        // $('#modal_role').text(dataResult.role);
        //$('#modal_shif').text(dataResult.id_shif);
         }
        });
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
        // $('#input_staff_id').focus();
        $('#input_rfid').prop('disabled', false);
        // $('#input_rfid').focus();
        $('#prefix_name').prop('disabled', false);
        // $('#prefix_name').focus();
        $('#input_name').prop('disabled', false);
        // $('#input_name').focus();
        $('#input_last').prop('disabled', false);
        // $('#input_last').focus();
        $('#role').prop('disabled', false);
        // $('#role').focus();
        $('#shift').prop('disabled', false);
        // $('#shift').focus();
        $('#input_site').prop('disabled', false);
        $('#button_rfid').hide();
        $('#button_save_rfid').show();
    });

});
