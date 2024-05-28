$(document).ready(function(){
    $('#button_save_rfid').hide();

    // Reset modal fields when it's closed
    $('#staff_modal').on('hide.bs.modal', function(){
        resetModalFields();
    });

    // Handle save button click
    $('#button_save_rfid').click(function () {
        var id_staff = $('#input_staff_id').val();
        var id_rfid = $('#input_rfid').val();
        var prefix = $('#prefix_name').val();
        var name = $('#input_name').val();
        var last = $('#input_last').val();
        var role_group = $('#id_role_group').val();
        var role = $('#id_role').val();
        var shift = $('#shift').val();
        var site = $('#input_site').val();

        $.ajax({
            url: "ajax/pp-staff-change-rfid.php",
            type: "GET",
            data: {
                id_staff: id_staff,
                id_rfid: id_rfid,
                name_first: name,
                name_last: last,
                prefix: prefix,
                id_role: role,
                id_role_group: role_group,
                id_shift: shift,
                site: site
            },
            cache: false,
            success: function(dataResult){
                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode == 200){
                    alert("Success!");
                    resetModalFields();
                } else if (dataResult.statusCode == 30) {
                    alert("Error: Duplicate active RFID");
                }
            }
        });
    });

    // Handle edit button click
    $('body').on('click', '.staff_edit', function(event){
        var selectedRow = $(this).parent().parent();
        var id_staff = selectedRow.find('.id_staff').html();
        var id_rfid = selectedRow.find('.rfid').html();
        var prefix = selectedRow.find('.prefix').html();
        var role_group = selectedRow.find('.role_group').html();
        var role = selectedRow.find('.role').html();
        var shift = selectedRow.find('.shift').html();

        var prefix_val = getPrefixVal(prefix);
        var role_group_val = getRoleGroupVal(role_group);
        var role_val = getRoleVal(role);

        $('#input_staff_id').val(id_staff);
        $('#input_rfid').val(id_rfid);
        $('#prefix_name').val(prefix_val);
        $('#id_role_group').val(role_group_val);
        $('#id_role').val(role_val);
        $('#shift').val(shift);

        $.ajax({
            url: "ajax/pp-staff-load.php",
            type: "GET",
            data: { id_staff: id_staff },
            cache: false,
            success: function(dataResult){
                var dataResult = JSON.parse(dataResult);
                $('#input_name').val(dataResult.name_first);
                $('#input_last').val(dataResult.name_last);
                $('#input_site').val(dataResult.site);
            }
        });

        $('#staff_modal').modal('show');
    });

    // เมื่อคลิกที่ปุ่ม "ลบ" ในรายการพนักงาน
    $('.staff_delete').click(function(){
        // เปิด Modal ยืนยันการลบ
        $('#confirmDeleteModal').modal('show');

        // รับรหัสพนักงานที่ต้องการลบ
        var id_staff = $(this).closest('.row_staff').find('.id_staff').text();

        // เมื่อคลิกที่ปุ่ม "ยืนยันการลบ"
        $('#confirmDeleteButton').click(function(){
            // ส่งคำร้องขอลบข้อมูลไปยังเซิร์ฟเวอร์
            $.ajax({
                url: 'ajax/pp-staff-delete.php',
                type: 'GET',
                data: { id_staff: id_staff },
                success: function(response){
                    // ปิด Modal หลังจากลบข้อมูลสำเร็จ
                    $('#confirmDeleteModal').modal('hide');
                    // ดำเนินการอื่นๆ ที่ต้องการหลังจากลบข้อมูล
                }
            });
        });
    });


    // Handle change button click to enable fields
    $('#button_rfid').click(function (){
        enableFields();
        $('#button_rfid').hide();
        $('#button_save_rfid').show();
    });

    function resetModalFields() {
        $('#input_staff_id').prop('disabled', true).val('');
        $('#input_rfid').prop('disabled', true).val('');
        $('#prefix_name').prop('disabled', true).val('');
        $('#input_name').prop('disabled', true).val('');
        $('#input_last').prop('disabled', true).val('');
        $('#id_role_group').prop('disabled', true).val('');
        $('#id_role').prop('disabled', true).val('');
        $('#shift').prop('disabled', true).val('');
        $('#input_site').prop('disabled', true).val('');
        $('#button_save_rfid').hide();
        $('#button_rfid').show();
    }

    function enableFields() {
        $('#input_staff_id').prop('disabled', false);
        $('#input_rfid').prop('disabled', false);
        $('#prefix_name').prop('disabled', false);
        $('#input_name').prop('disabled', false);
        $('#input_last').prop('disabled', false);
        $('#id_role_group').prop('disabled', false);
        $('#id_role').prop('disabled', false);
        $('#shift').prop('disabled', false);
        $('#input_site').prop('disabled', false);
    }

    function getPrefixVal(prefix) {
        if (prefix === 'นาย') return 1;
        if (prefix === 'นาง') return 2;
        if (prefix === 'นางสาว') return 3;
        return '';
    }

    function getRoleGroupVal(role_group) {
        if (role_group === 'Operator') return 1;
        if (role_group === 'Foreman') return 2;
        if (role_group === 'Admin') return 3;
        return '';
    }

    function getRoleVal(role) {
        if (role === 'Operator') return 1;
        if (role === 'Technician') return 2;
        if (role === 'Production Support') return 3;
        if (role === 'Instructor') return 4;
        if (role === 'Senior Instructor') return 5;
        if (role === 'Foreman') return 6;
        if (role === 'Leader') return 7;
        if (role === 'Senior Technician') return 8;
        if (role === 'Manager') return 9;
        if (role === 'Engineering') return 10;
        return '';
    }
});
