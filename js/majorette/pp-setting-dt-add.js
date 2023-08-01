$(document).ready(function() {
    $('input').on('keyup', isValid);


});
function isValid() {
    // alert('hello');
    let requiredInputs = $('input[required]');
    let emptyField = false;
    $.each(requiredInputs, function() {
        if( $(this).val().trim().length == 0 ) {
            emptyField = true;
            return false;
        }
    });
    if(!emptyField) {
        $('#submit_button').attr('disabled', false);
    }else{
        $('#submit_button').attr('disabled', true);
    }
}
