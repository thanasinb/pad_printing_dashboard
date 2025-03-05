$(document).ready(function(){
    $(document).ready(function(){
        $('#button_save_rfid').hide();

        $('#staff_modal').on('hide.bs.modal',function(){
            resetModalFields();
        });

        $('#button_save_rfid').click(function () {
            var id_staff = $('#input_staff_id').val();
            var id_rfid = $('#input_rfid').val();
            var prefix = $('#prefix_name').val();
            var name = $('#input_name').val();
            var last = $('#input_last').val();
            var roles = $('#role').val();
            var shift = $('#shift').val();
            var site = $('#input_site').val();

            // ตรวจสอบข้อมูลก่อนส่ง
            if (!id_staff || !id_rfid || !name || !last || !roles || !shift) {
                alert("Please fill all required fields.");
                return;
            }

            console.log("Sending data to server:", {
                id_staff: id_staff,
                id_rfid: id_rfid,
                name_first: name,
                name_last: last,
                prefix: prefix,
                id_role: roles,
                id_shif: shift,
                site: site
            });

            $.ajax({
                url: "ajax/pp-staff-change-rfid.php",
                type: "GET",
                data: {
                    id_staff: id_staff,
                    id_rfid: id_rfid,
                    name_first: name,
                    name_last: last,
                    prefix: prefix,
                    id_role: roles,
                    id_shif: shift,
                    site: site
                },
                cache: false,
                success: function(response){
                    console.log("Server response:", response);
                    var dataResult = JSON.parse(response);
                    if (dataResult.statusCode == 200) {
                        alert("Update successful!");
                        location.reload();
                    } else if (dataResult.statusCode == 30) {
                        alert("Error: Duplicate RFID detected.");
                    }
                },
                error: function(xhr, status, error){
                    console.error("AJAX error:", error);
                    alert("Failed to update staff data.");
                }
            });
        });

        $('body').on('click', '.staff_edit', function(event){
            var id_staff = $(this).closest('tr').find('.id_staff').text();

            console.log("Fetching data for staff ID:", id_staff);

            $.ajax({
                url: "ajax/pp-staff-load.php",
                type: "GET",
                data: { id_staff: id_staff },
                cache: false,
                success: function(response){
                    var dataResult = JSON.parse(response);
                    if (dataResult.statusCode === 200) {
                        $('#input_staff_id').val(dataResult.id_staff);
                        $('#input_rfid').val(dataResult.id_rfid);
                        $('#prefix_name').val(dataResult.prefix);
                        $('#input_name').val(dataResult.name_first);
                        $('#input_last').val(dataResult.name_last);
                        $('#input_site').val(dataResult.site);
                        $('#role').val(dataResult.id_role);
                        $('#shift').val(dataResult.id_shif);

                        // เปิดโมดอลแสดงข้อมูลที่โหลดมา
                        $('#staff_modal').modal('show');
                    } else {
                        alert("Error: Unable to fetch staff details.");
                    }
                },
                error: function(xhr, status, error){
                    console.error("Error fetching data:", error);
                    alert("Failed to load staff data.");
                }
            });
        });

        function resetModalFields() {
            $('#input_staff_id, #input_rfid, #prefix_name, #input_name, #input_last, #role, #shift, #input_site')
                .prop('disabled', true).val('');
            $('#button_save_rfid').hide();
            $('#button_rfid').show();
        }

        $('#button_rfid').click(function (){
            enableFields();
            $('#button_rfid').hide();
            $('#button_save_rfid').show();
        });

        function enableFields() {
            $('#input_staff_id, #input_rfid, #prefix_name, #input_name, #input_last, #role, #shift, #input_site')
                .prop('disabled', false);
        }
    });

    var delete_user_modal = document.getElementById('delete_user_modal');
    delete_user_modal.addEventListener('show.bs.modal', function (event) {
        // Button that triggered the modal
        // var button = event.relatedTarget;

        var selectedRow=$(event.relatedTarget).parent().parent();
        var modalIdStaffText = selectedRow.find('.id_staff').text();

        $('#modal_delete_user').html(modalIdStaffText);
    });

    $('#modal_button_delete').click(function (){
        var id_staff = $('#modal_delete_user').html();

        $.ajax({
            url: "ajax/pp-staff-delete.php",
            type: "GET",
            data: {
                id_staff: id_staff
            },
            context: this,
            cache: false,
            success: function(response) {
                var dataResult = JSON.parse(response);
                if (dataResult.statusCode === 200) {
                    alert('User deleted successfully');
                    location.reload();
                } else {
                    alert('Failed to delete user: ' + dataResult.message);
                }
            },
            error: function(err) {
                alert('Failed to delete user.');
            }
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
