$(document).ready(function() {
    // Reset modal fields when opening
    $('#setting_dt_modal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // ปุ่มที่เรียก Modal
        var selectedRow = button.closest('tr');

        var idStaff = selectedRow.find('.id_staff').text().trim();
        var firstName = selectedRow.find('.name_first').text().trim();
        var lastName = selectedRow.find('.name_last').text().trim();
        var username = selectedRow.find('.username').text().trim();

        console.log("Modal Opened - ID:", idStaff, "Username:", username);

        $('#modal_id_staff').val(idStaff);
        $('#modal_first_name').text(firstName);
        $('#modal_last_name').text(lastName);
        $('#modal_username').val(username);

        // Reset password field and eye icon
        $('#modal_password').val('');
        $('#modal_password').attr('type', 'password');
        $('#togglePassword i').removeClass('fa-eye').addClass('fa-eye-slash');
    });

    // Toggle password visibility
    $(document).ready(function() {
        $('#togglePassword').click(function() {
            var passwordField = $('#modal_password');
            var icon = $(this).find('i'); // ดึงไอคอนที่อยู่ภายในปุ่ม

            if (passwordField.attr('type') === 'password') {
                passwordField.attr('type', 'text'); // แสดงรหัสผ่าน
                icon.removeClass('fa-eye-slash').addClass('fa-eye'); // เปลี่ยนไอคอนเป็นตาเปิด
            } else {
                passwordField.attr('type', 'password'); // ซ่อนรหัสผ่าน
                icon.removeClass('fa-eye').addClass('fa-eye-slash'); // เปลี่ยนไอคอนเป็นตาปิด
            }
        });
    });


    // Confirm update button click
    $('#modal_button_confirm').click(function() {
        var idStaff = $('#modal_id_staff').val().trim();
        var username = $('#modal_username').val().trim();
        var password = $('#modal_password').val().trim();

        if (password === "") {
            alert('Please enter a new password before confirming!');
            return;
        }

        $.ajax({
            url: 'pp-account-user-update.php',
            type: 'POST',
            data: {
                id_staff: idStaff,
                username: username,
                password: password
            },
            success: function(response) {
                try {
                    var result = JSON.parse(response);
                    if (result.statusCode === 200) {
                        alert('User updated successfully');
                        location.reload();
                    } else {
                        alert('Failed to update user: ' + result.message);
                    }
                } catch (e) {
                    console.error("JSON Parse Error:", response);
                    alert('Unexpected response from server.');
                }
            },
            error: function(err) {
                alert('Failed to update user.');
                console.error("AJAX Error:", err);
            }
        });
    });

    // Open delete user modal
    $('#delete_user_modal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var selectedRow = button.closest('tr');
        var idStaff = selectedRow.find('.id_staff').text().trim();

        console.log("Delete Modal Opened - ID:", idStaff);

        $('#modal_delete_user').text(idStaff);
    });

    // Confirm delete button click
    $('#modal_button_delete').click(function() {
        var idStaff = $('#modal_delete_user').text().trim();

        if (!idStaff || isNaN(idStaff)) {
            alert('Invalid user ID');
            return;
        }

        $.ajax({
            url: "pp-account-user-delete.php",
            type: "GET",
            data: { id_staff: idStaff },
            success: function(response) {
                try {
                    var dataResult = JSON.parse(response);
                    if (dataResult.statusCode === 200) {
                        alert('User deleted successfully');
                        location.reload();
                    } else {
                        alert('Failed to delete user: ' + dataResult.message);
                    }
                } catch (e) {
                    console.error("JSON Parse Error:", response);
                    alert('Unexpected response from server.');
                }
            },
            error: function(err) {
                alert('Failed to delete user.');
                console.error("AJAX Error:", err);
            }
        });
    });
});
