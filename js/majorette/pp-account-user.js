$(document).ready(function() {
    // Function to handle the opening of the edit modal
    $('#setting_dt_modal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var selectedRow = button.closest('tr'); // Get the parent row of the button

        var idStaff = selectedRow.find('.id_staff').text();
        var firstName = selectedRow.find('.name_first').text();
        var lastName = selectedRow.find('.name_last').text();
        var username = selectedRow.find('.username').text();

        // Set the values in the edit modal
        $('#modal_id_staff').val(idStaff);
        $('#modal_first_name').text(firstName);
        $('#modal_last_name').text(lastName);
        $('#modal_username').val(username);

        // Clear password field and show placeholder for entering new password
        $('#modal_password').val(""); // Always set password as empty
        $('#hidden_placeholder').show(); // Show placeholder if exists

        // Hide placeholder when input starts
        $('#modal_password').on('input', function() {
            if ($(this).val().length > 0) {
                $('#hidden_placeholder').hide();
            } else {
                $('#hidden_placeholder').show();
            }
        });
    });

    // Function to handle the confirm button click in the edit modal
    $('#modal_button_confirm').click(function() {
        var idStaff = $('#modal_id_staff').val();
        var username = $('#modal_username').val();
        var password = $('#modal_password').val();

        // Ensure password is entered
        if (password.trim() === "") {
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

    // Function to handle the delete modal
    var delete_user_modal = document.getElementById('delete_user_modal');
    delete_user_modal.addEventListener('show.bs.modal', function (event) {
        var selectedRow = $(event.relatedTarget).closest('tr');
        var modalIdStaffText = selectedRow.find('.id_staff').text();

        $('#modal_delete_user').html(modalIdStaffText);
    });

    // Function to handle delete confirmation
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