$(document).ready(function(){
    // $('#label_shif_morning').text($('#label_shif_morning').text() + ' ' + shif_morning.getTime());
    // $('#label_shif_afternoon').text($('#label_shif_afternoon').text() + ' ' + shif_afternoon.getTime());
    // $('#label_shif_night').text($('#label_shif_night').text() + ' ' + shif_night.getTime());
    //
    // $.ajax({
    //     url: "ajax/pp-export-query.php",
    //     type: "GET",
    //     data: {
    //         shif: shif
    //     },
    //     context: this,
    //     cache: false,
    //     success: function(dataResult){
    //         var dataResult = JSON.parse(dataResult);
    //     }
    // });

    $('#button_export').click(function (){
        $('.table_to_be_exported').each(function (i, obj) {
            // alert($(this).html());
            // var elt = $('.table_to_be_exported')[0];
            // var wb = XLSX.utils.table_to_book(elt, {sheet:"Sheet JS"});
            // XLSX.writeFile(wb, 'SheetJSTableExport.xlsx');

            var wb = XLSX.utils.table_to_book($(this)[0], {sheet:"Sheet JS"});
            XLSX.writeFile(wb, 'SheetJSTableExport.xlsx');


            // var table_info, table_records, json_table_info, json_table_records;
            // if (i%2==1){
            //     // table_records = XLSX.utils.table_to_book($(this)[0], {sheet:"Sheet JS"});
            //     table_records = XLSX.utils.table_to_sheet($(this)[0]);
            //     json_table_records = XLSX.utils.sheet_to_json(table_records, { header: 1 })
            //     json_table_info = json_table_info.concat(['']).concat(json_table_records);
            //     let worksheet = XLSX.utils.json_to_sheet(json_table_info, { skipHeader: true })
            //     let workbook = XLSX.utils.book_new();
            //     XLSX.utils.book_append_sheet(workbook, worksheet, "Sheet JS");
            //     XLSX.writeFile(workbook, 'SheetJSTableExport.xlsx');
            // }
            // else {
            //     // table_info = XLSX.utils.table_to_book($(this)[0], {sheet:"Sheet JS"});
            //     table_info = XLSX.utils.table_to_sheet($(this)[0]);
            //     json_table_info = XLSX.utils.sheet_to_json(table_info, { header: 1 })
            // }
        });

    });

    $('input[name="radio_shif"]').click(function (){
        $('#button_list').prop('disabled', false);
    });

    $('#button_list').click(function (){
        var d = new Date();
        var year = d.getFullYear();
        var month = ('0' + (d.getMonth()+1)).slice(-2);
        var date_today = ('0' + d.getDate()).slice(-2);
        var date_yesterday = ('0' + (d.getDate()-1)).slice(-2);
        var shif_start, shif_end;
        var shif_letter;

        var shif = $('input[name="radio_shif"]:checked').val();
        if (shif == 'morning'){
            shif_start = year + '-' + month + '-' + date_today + ' ' + '07:00:00';
            shif_end   = year + '-' + month + '-' + date_today + ' ' + '15:00:00';
            shif_letter = 'M';
        }else if (shif == 'afternoon'){
            shif_start = year + '-' + month + '-' + date_today + ' ' + '15:00:00';
            shif_end   = year + '-' + month + '-' + date_today + ' ' + '23:00:00';
            shif_letter = 'A';
        }else if (shif == 'night'){
            shif_start = year + '-' + month + '-' + date_yesterday + ' ' + '23:00:00';
            shif_end   = year + '-' + month + '-' + date_today     + ' ' + '07:00:00';
            shif_letter = 'N';
        }
        // alert(shif_start + ' ' + shif_end);
        $.ajax({
            url: "ajax/pp-export-query-2.php",
            type: "GET",
            data: {
                shif_start: shif_start,
                shif_end: shif_end,
                shif_letter: shif_letter
            },
            context: this,
            cache: false,
            success: function(dataResult){
                var dataResult = JSON.parse(dataResult);
                var qty_accum;
                var date_start;
                var table_data, table_header;
                var id_machine;
                // alert(JSON.stringify(dataResult));
                $('.row_export').remove();
                // alert(JSON.stringify(dataResult[0][0][0]));
                // alert(JSON.stringify(dataResult[0][0][1]));
                // alert(JSON.stringify(dataResult[1][0][0]));
                // alert(JSON.stringify(dataResult[1][0][1]));

                // FOR EACH TASK
                for (let i in dataResult) {
                    qty_accum = 0;
                    // alert(JSON.stringify(dataResult[0][i]));
                    // alert(dataResult[i].id_job);

                    // FOR EACH MACHINE
                    for (let j in dataResult[i][0]) {
                        // alert(JSON.stringify(dataResult[i][0]));

                        table_data = $('<table><tbody><tr><th>Date</th><th>Shif</th><th>Operator No.</th><th>Runtime (Hrs)</th><th>Qty Process</th><th>Qty Accum</th></tr>');

                        // FOR EACH ACTIVITY
                        for (let k in dataResult[i][0][j]){
                            // FOR THE FIRST ACTIVITY, TAKE MACHINE ID, TO PUT IN THE HEADER TABLE
                            if (k==0){
                                id_machine = dataResult[i][0][j][k].id_machine;
                            }
                            date_start = new Date(dataResult[i][0][j][k].time_start);
                            qty_accum += parseInt(dataResult[i][0][j][k].no_pulse1);

                            table_data.append('<tr class="row_export">' +
                                '<td>' +  date_start.toLocaleDateString('es-CL')  + '</td>' +
                                '<td>' + dataResult[i][0][j][k].id_shif + '</td>' +
                                '<td>' + dataResult[i][0][j][k].id_staff + '</td>' +
                                '<td>' + dataResult[i][0][j][k].total_work  + '</td>' +
                                '<td>' + parseInt(dataResult[i][0][j][k].no_pulse1).toLocaleString('en-US') + '</td>' +
                                '<td>' + qty_accum.toLocaleString('en-US') + '</td>' +
                                '</td></tr>');

                        }
                        table_header = $('<table><thead>' +
                            '<tr><th>Work Center</th><th>Line</th><th>Operation</th><th>Description</th><th>Machine NO.</th><th>Set up By</th>' +
                            '<th>ID</th><th>Item Number</th><th>Description</th><th>Qty Order</th></tr></thead>' +
                            '<tbody><tr><td>' + dataResult[i].work_center + '</td><td>' + dataResult[i].prod_line + '</td><td>' + dataResult[i].operation + '</td>' +
                            '<td>' + dataResult[i].op_des + '</td><td>' + id_machine + '</td><td></td><td>' + dataResult[i].id_job + '</td><td>' + dataResult[i].item_no + '</td>' +
                            '<td>' + dataResult[i].item_des + '</td><td>' + parseInt(dataResult[i].qty_order).toLocaleString('en-US') + '</td></tr></tbody></table>');

                        $('#div_table_export').append(table_header);
                        $('#div_table_export').append(table_data);
                        $('#div_table_export').append('<br>');
                    }
                    $('#div_table_export table').find('tr:first').addClass('fw-bold');
                    $('thead').addClass('table-dark');
                    $('#div_table_export table').addClass('table table-striped table-sm table_to_be_exported');
                }
                $('#card_table_export').prop('hidden', false);
            }
        });
    });
});