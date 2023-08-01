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
        var modalDateSettingText = selectedRow.find('.date_setting').text();
        var modalBoxCode = setting_dt_modal.querySelector('#modal_box_code');

        modalBoxCode.textContent=modalBoxCodeText;
        $('#modal_downtime_code').val(modalDowntimeCodeText);
        $('#modal_des_eng').val(modalDesEngText);
        $('#modal_des_tha').val(modalDesThaText);
        $('#modal_date_setting').val(modalDateSettingText);
    });

    $('#modal_button_save').click(function (){
        var modalBoxCode = $('#modal_box_code').html();
        var modalDowntimeCode = $('#modal_downtime_code').val();
        var modalDesEng = $('#modal_des_eng').val();
        var modalDesTha = $('#modal_des_tha').val();

        $.ajax({
            url: "ajax/pp-setting-dt-update.php",
            type: "GET",
            data: {
                box_code: modalBoxCode,
                downtime_code: modalDowntimeCode,
                des_eng : modalDesEng,
                des_tha : modalDesTha
            },
            context: this,
            cache: false,
            success: function(dataResult){
                var dataResult = JSON.parse(dataResult);
                location.reload();
            }
        });
    });
});
