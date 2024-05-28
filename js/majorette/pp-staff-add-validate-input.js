$(document).ready(function() {
    $('input[required]').on('keyup', checkFormValidity);
    $('select[required]').on('change', checkFormValidity);

    // Initial check to ensure the button state is correct on page load
    checkFormValidity();
});

function checkFormValidity() {
    let isFormValid = true;

    // Check all required input fields
    $('input[required]').each(function() {
        if ($(this).val().trim().length === 0) {
            isFormValid = false;
            return false; // Break the loop if an empty field is found
        }
    });

    // Check all required select fields
    $('select[required]').each(function() {
        if ($(this).val().trim().length === 0) {
            isFormValid = false;
            return false; // Break the loop if an empty field is found
        }
    });

    // Enable or disable the submit button based on form validity
    $('#submit_button').attr('disabled', !isFormValid);
}
