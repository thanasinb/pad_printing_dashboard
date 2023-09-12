var frequency = 5000; // 5 seconds in miliseconds
var interval_update = 0;
var sort_key='id_mc';
var sort_dir=1;

$(document).ready(function(){
    loadData();
    startLoop();

    $('#dash_machine').click(function () {
        sort_key='id_mc';
        sort_dir=1;
    });
    $('#dash_percent').click(function () {
        sort_key='percent';
        sort_dir=-1;
    });
    $('#dash_open').click(function () {
        sort_key='est_sec';
        sort_dir=1;
    });
});

// STARTS and Resets the loop
function startLoop() {
    if (interval_update > 0) clearInterval(interval_update); // stop
    interval_update = setInterval("loadData()", frequency); // run
}

function sort_by_key(array, key, dir)
{
    if(dir==1){
        return array.sort(function(a, b)
        {
            var x = a[key]; var y = b[key];
            return ((x < y) ? -1 : ((x > y) ? 1 : 0));
        });
    }else if(dir==-1){
        return array.sort(function(a, b)
        {
            var x = a[key]; var y = b[key];
            return ((x > y) ? -1 : ((x < y) ? 1 : 0));
        });
    }
}

function loadData() {
    $.ajax({
        url: "ajax/pp-machine-refresh-3.php",
        type: "GET",
        context: this,
        cache: false,
        success: function(dataResult){
            var data = JSON.parse(dataResult);
            data = sort_by_key(data, sort_key, sort_dir);
            $("#table_body tr").remove();
            $.each(data, function(i, item) {
                if(item.item_no==null){
                    if(!$('#checkbox_hide_unassigned_machines').is(":checked")){
                        var row = "<tr class=\"text-black fw-bold\"><td></td><td>" + item.id_mc + "</td>" +
                            "<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>" +
                            "<td></td><td></td><td></td><td></td><td></td></tr>";
                    }
                }
                else {
                    var row = "<tr class=\"text-black fw-bold\"><td></td>" +
                        "<td>" + item.id_mc + "</td>" +
                        "<td>" + item.item_no + "</td>" +
                        "<td>" + item.operation + "</td>" +
                        "<td>" + item.op_color + "</td>" +
                        "<td>" + item.op_side + "</td>" +
                        "<td>" + item.date_due + "</td>" +
                        "<td>" + item.qty_per_tray + "</td>" +
                        "<td>" + item.qty_accum.toLocaleString('en-US') + "/" + item.qty_order.toLocaleString('en-US') + "</td>" +
                        "<td><div class=\"progress\"><div id=\"progress-bar\" class=\"progress-bar\" role=\"progressbar\" style=\"width: " +
                        item.percent + "%\" aria-valuenow=\"" + item.percent + "\" aria-valuemin=\"0\" aria-valuemax=\"100\">" +
                        item.percent + "%</div></div></td>" +
                        "<td>" + parseFloat(item.run_time_actual).toFixed(2) + "/" + item.run_time_std + "</td>" +
                        "<td>" + item.run_time_open + "</td>" +
                        "<td>" + item.est_time + "</td>" +
                        "<td></td><td></td></tr>"
                }
                $('#table_body').append(row);
            });
        }
    });

}
