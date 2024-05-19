$(document).ready(function(){
    $('#add_user_form').on('submit', function(event){
        event.preventDefault();

        console.log("Form submitted"); // Debugging line

        $.ajax({
            url: 'pp-account-user-add.php', // Ensure this path is correct
            method: 'POST',
            data: $(this).serialize(),
            success: function(data){
                console.log(data); // Debugging line to print the response
                $('#add_user_modal').modal('hide');
                location.reload(); // Reload the page to see the updated user list
            },
            error: function(xhr, status, error){
                console.error('Error adding user:', error); // Debugging line
                alert('Error adding user');
            }
        });
    });
});
