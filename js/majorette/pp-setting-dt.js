$(document).ready(function(){
    $('#button_save_rfid').hide();
    $('#setting_dt_modal').on('hide.bs.modal',function(){
        $('#input_setting_dt_id').prop('disabled', true);
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
        var id_setting_dt = $('#input_setting_dt_id').val();
        var id_rfid = $('#input_rfid').val();
        var prefix= $('#prefix_name').val();
        var name= $('#input_name').val();
        var last= $('#input_last').val();
        var roles= $('#role').val();
        var shift= $('#shift').val();
        var site= $('#input_site').val();
        $.ajax({
            url: "ajax/pp-staff-change-rfid.php",
            type: "GET",
            data: {
                id_setting_dt: id_setting_dt,
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
                var dataResult = JSON.parse(dataResult);
                $('#input_setting_dt_id').text(id_setting_dt);
                $('#input_setting_dt_id').prop('disabled', true);
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
                $('#button_save_rfid').hide();
                $('#button_rfid').show();
            }
        });

    });

    $('body').on('click', '.setting_dt_edit', function(event){
        var id_setting_dt = $(this).parent().parent().find('.id_setting_dt').html();
        var id_rfid = $(this).parent().parent().find('.rfid').html();
        var prefix = $(this).parent().parent().find('.prefix').html();
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
        }else if(role==='Technician'){
            role_val=2;
        }else if(role==='Production Support'){
            role_val=3;
        }else if(role==='Instructor'){
            role_val=4;
        }else if(role==='Senior Instructor'){
            role_val=5;
        }else if(role==='Foreman'){
            role_val=6;
        }else if(role==='Leader'){
            role_val=7;
        }else if(role==='Senior Technician'){
            role_val=8;
        }else if(role==='Manager'){
            role_val=9;
        }else if(role==='Engineering'){
            role_val=10;
        }
        //alert(prefix + prefix_val + role + role_val+ shif);

        $('#input_setting_dt_id').val(id_setting_dt);
        $('#input_rfid').val(id_rfid);
        $('#prefix_name').val(prefix_val);
        //$('#input_name').val(name_first);
        //$('#input_last').val(name_last);
        $('#role').val(role_val);
        $('#shift').val(shif);

        $('#setting_dt_modal').modal('show');
        $.ajax({
         url: "ajax/pp-staff-load.php",
        type: "GET",
        data: {
         id_setting_dt: id_setting_dt
         },
        context: this,
        cache: false,
        success: function(dataResult){
         var dataResult = JSON.parse(dataResult);
         $('#input_name').val(dataResult.name_first);
         $('#input_last').val(dataResult.name_last);
         $('#input_site').val(dataResult.site);
         }
        });
    });

    $('.setting_dt_delete').click(function (){
        var id_setting_dt = $(this).parent().parent().find('.id_setting_dt').html();
        $.ajax({
            url: "ajax/pp-staff-delete.php",
            type: "GET",
            data: {
                id_setting_dt  : id_setting_dt
            },
            context: this,
            cache: false,
            success: function(dataResult){
                var dataResult = JSON.parse(dataResult);
                $('.row_setting_dt:contains(' + id_setting_dt + ')').remove();
            }
        });
    });

    $('#button_rfid').click(function (){
        $('#input_setting_dt_id').prop('disabled', false);
        $('#input_rfid').prop('disabled', false);
        $('#prefix_name').prop('disabled', false);
        $('#input_name').prop('disabled', false);
        $('#input_last').prop('disabled', false);
        $('#role').prop('disabled', false);
        $('#shift').prop('disabled', false);
        $('#input_site').prop('disabled', false);
        $('#button_rfid').hide();
        $('#button_save_rfid').show();
    });

});
