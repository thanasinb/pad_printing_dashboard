var dateNow = new Date();var complete_date_picker_0 = function () {
    var complete_date_0 = function () {
        $('#datetimepicker_date_0').datetimepicker({
            format: 'DD-MM-YYYY',
            defaultDate: new Date('08/21/2021'),
            useCurrent: false
        });
        $('#datetimepicker_date_0').on('change.datetimepicker', function () {
            alert($('#input_date_0').val());
        });
    }
    return {
        init: function() {
            complete_date_0();
        }
    };
}();
var complete_time_picker_0 = function () {
    var complete_time_0 = function () {
        $('#datetimepicker_time_0').datetimepicker({
            format: 'HH:mm',
            defaultDate: moment(dateNow).hours(16).minutes(30).seconds(0).milliseconds(0),
            useCurrent: false
        });
        $('#datetimepicker_time_0').on('change.datetimepicker', function () {
            alert($('#input_time_0').val());
        });
    }
    return {
        init: function() {
            complete_time_0();
        }
    };
}();
var complete_date_picker_1 = function () {
    var complete_date_1 = function () {
        $('#datetimepicker_date_1').datetimepicker({
            format: 'DD-MM-YYYY',
            useCurrent: false
        });
        $('#datetimepicker_date_1').on('change.datetimepicker', function () {
            alert($('#input_date_1').val());
        });
    }
    return {
        init: function() {
            complete_date_1();
        }
    };
}();
var complete_time_picker_1 = function () {
    var complete_time_1 = function () {
        $('#datetimepicker_time_1').datetimepicker({
            format: 'HH:mm',
            useCurrent: false
        });
        $('#datetimepicker_time_1').on('change.datetimepicker', function () {
            alert($('#input_time_1').val());
        });
    }
    return {
        init: function() {
            complete_time_1();
        }
    };
}();
jQuery(document).ready(function() {
    complete_date_picker_0.init();
    complete_time_picker_0.init();
    complete_date_picker_1.init();
    complete_time_picker_1.init();
});