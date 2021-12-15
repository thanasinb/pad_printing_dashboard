var frequency = 15000; // 15 seconds in miliseconds
var interval = 0;
var id_machine;
var qty_complete;
var qty_order;
var qty_accum;
var qty_process;
var percent;

$(document).ready(function(){
    // progress_bar = document.getElementById("progress-bar");
    id_machine = document.getElementById("id_machine").value;
    qty_complete = document.getElementById("qty_comp").value;
    qty_order = document.getElementById("qty_order").value;

    // alert(id_machine);
    startLoop();
});

// STARTS and Resets the loop
function startLoop() {
    if (interval > 0) clearInterval(interval); // stop
    interval = setInterval("loadData()", frequency); // run
}

function loadData() {
    $.ajax({
        url: "ajax/pp-machine-info-refresh.php",
        type: "POST",
        data: {
            id_mc: id_machine
        },
        cache: false,
        success: function(dataResult){
            var dataResult = JSON.parse(dataResult);
            qty_process = parseInt(dataResult.no_pulse1);
            qty_accum = parseInt(qty_complete)+qty_process;
            percent = Math.round((qty_accum/parseInt(qty_order))*100);
            $('#progress-bar').css('width', percent+'%').attr('aria-valuenow', percent);
            document.getElementById("span_percent").textContent=percent.toString().concat("%");
            document.getElementById("span_qty_process").textContent="Qty process: " + qty_process.toString();
            document.getElementById("span_qty_accum").textContent="Qty accum: " + qty_accum.toString();
            // alert(percent);
        }
    });
}
