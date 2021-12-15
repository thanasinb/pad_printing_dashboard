var frequency = 10000; // 10 seconds in miliseconds
// var interval_blink = 0;
var interval_update = 0;

$(document).ready(function(){
    loadData();
    startLoop();
    // loadData();
});

// STARTS and Resets the loop
function startLoop() {
    if (interval_update > 0) clearInterval(interval_update); // stop
    interval_update = setInterval("loadData()", frequency); // run

    // if (interval_blink > 0) clearInterval(interval_blink); // stop
    // interval_blink = setInterval("setBlink()", frequency); // run
}

// function setBlink() {
//     $('.will_blink').addClass('blink_me');
// }

function loadData() {
    $('.id_machine').each(function(i, obj) {
        var id_machine;
        var qty_process;
        var qty_accum;
        var qty_complete;
        var qty_order;
        var percent;
        var item_no;
        var run_time_std;
        var run_time_actual;
        var run_time_open;
        var status_work;
        const options = {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        };

        id_machine = $(this).html();
        item_no = $(this).next().text();
        // alert($(this).parent().find('.progress-bar').attr('aria-valuenow'));
        // alert($(this).parent().find('.progress-bar').css('width'));
        // alert($(this).parent().find('.progress-bar').text());
        // alert($(this).parent().find('.qty_accum_order').html());
        if (item_no!=''){
            // alert(item_no);
            $.ajax({
                url: "ajax/pp-machine-refresh.php",
                type: "GET",
                data: {
                    id_mc: id_machine
                },
                context: this,
                cache: false,
                success: function(dataResult){
                    var dataResult = JSON.parse(dataResult);
                    qty_process = parseInt(dataResult.qty_process);
                    qty_order = parseInt(dataResult.qty_order);
                    qty_complete = parseInt(dataResult.qty_complete);

                    qty_accum = qty_complete+qty_process;
                    percent = Math.round((qty_accum/qty_order)*100);

                    run_time_std = parseFloat(dataResult.run_time_std);
                    run_time_actual = parseFloat(dataResult.run_time_actual);
                    run_time_open = (qty_order-qty_accum)*run_time_std;

                    status_work = parseInt(dataResult.status_work);

                    $(this).parent().find('.progress-bar').text(percent + '%');
                    $(this).parent().find('.progress-bar').attr('aria-valuenow', percent);
                    $(this).parent().find('.progress-bar').css('width', percent + "%")
                    $(this).parent().find('.qty_accum_order').html(qty_accum.toLocaleString('en-US') + ' / ' + qty_order.toLocaleString('en-US'));
                    $(this).parent().find('.run_time').text(run_time_actual.toLocaleString('en-US', options) + ' / ' + run_time_std.toLocaleString('en-US', options));
                    $(this).parent().find('.run_time_open').text(new Date(run_time_open * 1000).toISOString().substr(11, 8));
                    // run_time_open.toLocaleString('en-US', options));
                    if(run_time_actual>run_time_std){
                        // $(this).parent().find('.run_time').addClass('will_blink');
                        $(this).parent().find('.run_time').addClass('blink_me');
                        $(this).parent().find('.run_time').addClass('text-red');
                    }else {
                        $(this).parent().find('.run_time').removeClass('blink_me');
                        $(this).parent().find('.run_time').removeClass('text-red');
                        // $(this).parent().find('.run_time').removeClass('will_blink');
                    }
                    if(status_work==0){
                        $(this).parent().find('.status_work').removeClass('text-green');
                        $(this).parent().find('.status_work').removeClass('text-yellow');
                        $(this).parent().find('.status_work').addClass('text-blue');
                    }else if(status_work==1){
                        $(this).parent().find('.status_work').removeClass('text-blue');
                        $(this).parent().find('.status_work').removeClass('text-yellow');
                        $(this).parent().find('.status_work').addClass('text-green');
                    }else if(status_work==2){
                        $(this).parent().find('.status_work').removeClass('text-blue');
                        $(this).parent().find('.status_work').removeClass('text-green');
                        $(this).parent().find('.status_work').addClass('text-yellow');
                    }
                }
            });
        }
    });
}
