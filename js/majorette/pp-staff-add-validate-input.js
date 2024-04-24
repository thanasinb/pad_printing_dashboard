$(document).ready(function() {
    $('input').on('keyup', isValid);
    $('select').on('change', isValid);
});
function isValid() {
    // alert($('#id_shif').val()=="");
    let requiredInputs = $('input[required]');
    let emptyField = false;
    $.each(requiredInputs, function() {
        if( $(this).val().trim().length == 0 ) {
            emptyField = true;
            return false;
        }
    });
    let requiredSelect = $('select[required]');
    $.each(requiredSelect, function() {
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
