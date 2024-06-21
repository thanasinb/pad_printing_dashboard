$(document).ready(function() {
    // Function to handle the opening of the edit modal
    $('#setting_dt_modal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var selectedRow = button.closest('tr');

        var idStaff = selectedRow.find('.id_staff').text();
        var firstName = selectedRow.find('.name_first').text();
        var lastName = selectedRow.find('.name_last').text();
        var username = selectedRow.find('.username').text();
        var password = selectedRow.find('.password').text();

        // Set the values in the edit modal
        $('#modal_id_staff').val(idStaff);
        $('#modal_first_name').text(firstName);
        $('#modal_last_name').text(lastName);
        $('#modal_username').val(username);
        $('#modal_password').val(password);
    });

    // Function to handle the confirm button click in the edit modal
    $('#modal_button_confirm').click(function() {
        var idStaff = $('#modal_id_staff').val();
        var username = $('#modal_username').val();
        var password = $('#modal_password').val();

        $.ajax({
            url: 'pp-account-user-update.php',
            type: 'POST',
            data: {
                id_staff: idStaff,
                username: username,
                password: password
            },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.statusCode === 200) {
                    alert('User updated successfully');
                    location.reload();
                } else {
                    alert('Failed to update user: ' + result.message);
                }
            },
            error: function(err) {
                alert('Failed to update user.');
            }
        });
    });

    var delete_user_modal = document.getElementById('delete_user_modal');
    delete_user_modal.addEventListener('show.bs.modal', function (event) {
        var selectedRow=$(event.relatedTarget).parent().parent();
        var modalIdStaffText = selectedRow.find('.id_staff').text();

        $('#modal_delete_user').html(modalIdStaffText);
    });

    $('#modal_button_delete').click(function (){
        var idStaff = $('#modal_delete_user').html();

        $.ajax({
            url: "pp-account-user-delete.php",
            type: "GET",
            data: {
                id_staff: idStaff
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
});
