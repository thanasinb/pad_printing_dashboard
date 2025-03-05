$(document).ready(function() {
    // แสดงข้อมูลใน Modal เมื่อเปิดสำหรับการแก้ไข Machine
    $('#setting_mc_modal').on('show.bs.modal', function (event) {
        var selectedRow = $(event.relatedTarget).closest('tr');
        var modalIdMachineText = selectedRow.find('.id_mc').text().trim();
        var modalMachineTypeText = selectedRow.find('.id_mc_type').attr('data-id-mc-type') || "";
        var modalDesMachineText = selectedRow.find('.mc_des').text().trim();
        var modalIdCameraText = selectedRow.find('.id_cam').text().trim();

        // ตั้งค่าค่าปัจจุบันใน modal
        $('#modal_id_mc').text(modalIdMachineText);
        $('#modal_id_mc_type').val(modalMachineTypeText);
        $('#modal_mc_des').val(modalDesMachineText);
        $('#modal_id_cam').val(modalIdCameraText);

        // ตรวจสอบค่าของ select ว่าตรงกับค่าใน database หรือไม่
        $('#modal_id_mc_type option').each(function() {
            if ($(this).val() === modalMachineTypeText) {
                $(this).prop('selected', true);
            }
        });
    });

    // บันทึกการแก้ไขเมื่อกดปุ่ม Save
    $('#modal_button_save').click(function () {
        var id_mc = $('#modal_id_mc').text().trim();
        var id_mc_type = $('#modal_id_mc_type').val().trim();
        var mc_des = $('#modal_mc_des').val().trim();
        var id_cam = $('#modal_id_cam').val().trim();

        $.ajax({
            url: "ajax/pp-setting-mc-update.php",
            type: "POST",
            data: {
                id_mc: id_mc,
                id_mc_type: id_mc_type,
                mc_des: mc_des,
                id_cam: id_cam
            },
            success: function(response) {
                console.log("Server Response: ", response);
                try {
                    var data = JSON.parse(response);
                    if (data.statusCode === 200) {
                        alert("Update successful.");
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                } catch (e) {
                    alert("Unexpected error occurred.");
                    console.error("JSON Parsing Error: ", e);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: ", status, error);
                alert("Failed to update machine.");
            }
        });
    });



// แสดงข้อมูลใน Modal สำหรับการลบ Machine
    $('#delete_mc_modal').on('show.bs.modal', function (event) {
        var selectedRow = $(event.relatedTarget).closest('tr');
        var modalBoxCodeText = selectedRow.find('.id_mc').text();
        $('#modal_delete_machine_id').text(modalBoxCodeText);
    });

    // ลบ Machine เมื่อกดปุ่ม Delete
    $('#modal_button_delete').click(function () {
        var modalBoxCode = $('#modal_delete_machine_id').text();

        $.ajax({
            url: "ajax/pp-setting-mc-delete.php",
            type: "GET",
            data: {
                id_mc: modalBoxCode
            },
            cache: false,
            success: function (response) {
                var dataResult = JSON.parse(response);
                if (dataResult.statusCode === 200) {
                    alert("Machine deleted successfully.");
                } else {
                    alert(dataResult.message);
                }
                location.reload();
            },
            error: function () {
                alert("Failed to delete machine.");
            }
        });
    });
});
