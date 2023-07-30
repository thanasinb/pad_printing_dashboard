$(document).ready(function(){

    var setting_dt_modal = document.getElementById('setting_dt_modal');
    setting_dt_modal.addEventListener('show.bs.modal', function (event) {
        // Button that triggered the modal
        // var button = event.relatedTarget;

        var selectedRow=$(event.relatedTarget).parent().parent();
        var modalBoxCodeText = selectedRow.find('.box_code').text();
        var modalDowntimeCodeText = selectedRow.find('.code_downtime').text();
        var modalDesEngText = selectedRow.find('.des_downtime_eng').text();
        var modalDesThaText = selectedRow.find('.des_downtime_tha').text();
        var modalStatusEnableVal = selectedRow.find('#status_enable_hidden').val();
        var modalDateSettingText = selectedRow.find('.date_setting').text();

        $('#modal_box_code').val(modalBoxCodeText);
        $('#modal_downtime_code').val(modalDowntimeCodeText);
        $('#modal_des_eng').val(modalDesEngText);
        $('#modal_des_tha').val(modalDesThaText);
        $('#modal_status_enable').val(modalStatusEnableVal);
        $('#modal_date_setting').val(modalDateSettingText);
    });

});
