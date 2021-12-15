$(document).ready(function(){
    $('.dropdown-item').click(function(){
        var id_task=$(this).data("id-task");
        var id_mc=$(this).data("id-mc");
        var id_mc_old=$(this).data("id-mc-old");
        var queue=$(this).data("queue");
        $.ajax({
            url: "ajax/pp-planning-assign-machine.php",
            type: "POST",
            data: {
                id_task: id_task,
                id_mc: id_mc,
                id_mc_old: id_mc_old,
                queue: queue
            },
            cache: false,
            success: function(dataResult){
                // location.reload();
                // $('.nav-fixed').load('pp-planning.php .nav-fixed');
                // var dataResult = JSON.parse(dataResult);
                // // alert(dataResult.sql.toString());
                // if(dataResult.statusCode1==200 && dataResult.statusCode2==200){
                //     alert('Data added successfully !');
                // }
                // else {
                //     alert("Error occured !");
                //     // alert(dataResult.sql);
                // }
            }
        });
    });
    $('.pos-up').click(function(){
        // var id_task=$(this).data("id-task");
        // var queue=$(this).data("queue");
        var id_mc=$(this).data("id-mc");
        var edge = $(this).data("edge");
        var row = this.parentNode.parentNode.parentNode;
        var id_task = $(row).data("id-task");
        var queue = $(row).data("queue");

        var row_prev = row.previousSibling;
        var row_text = row.cells[0].childNodes[0].childNodes[0].innerHTML;
        var is_top_row = false;
        try{
            var row_prev_text = row_prev.cells[0].childNodes[0].childNodes[0].innerHTML;
        }catch (e) {
            is_top_row = true;
        }
        var is_last_row = false; // last row that is assigned a queue.
        try{
            var row_next_text = row_next.cells[0].childNodes[0].childNodes[0].innerHTML;
        }
        catch (e) {
            is_last_row = true;
        }
        if(!is_top_row){
            if(!(row_text.localeCompare(row_prev_text))){
                // if(is_last_row){
                //     alert()
                // }
                $.ajax({
                    url: "ajax/pp-planning-queue-up.php",
                    type: "POST",
                    data: {
                        id_task: id_task,
                        id_mc: id_mc,
                        queue: queue
                    },
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        // alert(dataResult.task_prev + dataResult.task_next);
                        // alert(dataResult.sql1 + dataResult.sql2);
                        // alert(dataResult.sql1);

                        var sibling_previous = row.previousElementSibling;
                        var parent = row.parentNode;
                        parent.insertBefore(row, sibling_previous);

                        // $(row).data("id-task", dataResult.task_next);
                        // $(row_prev).data("id-task", dataResult.task_prev);

                        $(row).data("queue", queue.toString());
                        $(row_prev).data("queue", (queue-1).toString());
                    }
                });
            }
        }
    });
    $('.pos-down').click(function(){
        var id_task=$(this).data("id-task");
        var id_mc=$(this).data("id-mc");
        var queue=$(this).data("queue");
        var edge=$(this).data("edge");
        var row = this.parentNode.parentNode.parentNode;
        var row_next = row.nextSibling;
        var row_text = row.cells[0].childNodes[0].childNodes[0].innerHTML;
        var is_last_row = false; // last row that is assigned a queue.
        try{
            var row_next_text = row_next.cells[0].childNodes[0].childNodes[0].innerHTML;
        }
        catch (e) {
            is_last_row = true;
        }
        if(!is_last_row) {
            if (!(row_text.localeCompare(row_next_text))) {
                $.ajax({
                    url: "ajax/pp-planning-queue-down.php",
                    type: "POST",
                    data: {
                        id_task: id_task,
                        id_mc: id_mc,
                        queue: queue
                    },
                    cache: false,
                    success: function(dataResult){
                        var sibling_next = row.nextSibling;
                        var parent = row.parentNode;
                        parent.insertBefore(sibling_next, row);
                    }
                });
            }
        }
    });
});