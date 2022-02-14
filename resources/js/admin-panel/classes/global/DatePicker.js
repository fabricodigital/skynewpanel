export default class DatePicker {

    constructor() {
        this.init();
        this.initDataTimePicker();
        this.initDateRange();
        this.initDateTimeRange();
    }

    init() {
        $('.date-picker').each(function () {
            var current = $(this);
            var locale = $.extend(true, {}, daterangepickerLocale, {
                format: 'DD/MM/YYYY'
            });
            current.daterangepicker({
                singleDatePicker: true,
                autoUpdateInput: false,
                showDropdowns: true,
                locale: locale
            }, function(start, end, label) {
                $('#' + current.data('field')).val(start.format('DD/MM/YYYY'));
                current.val(start.format('DD/MM/YYYY'));
            });
        });
    }

    initDataTimePicker() {
        $('.date-time-picker').each(function () {
            var current = $(this);
            var locale = $.extend(true, {}, daterangepickerLocale, {
                format: 'DD/MM/YYYY HH:mm'
            });
            current.daterangepicker({
                timePicker: true,
                timePicker24Hour: true,
                timePickerIncrement: 1,
                singleDatePicker: true,
                autoUpdateInput: false,
                showDropdowns: true,
                locale: locale
            }, function(start, end, label) {
                $('#' + current.data('field')).val(start.format('DD/MM/YYYY HH:mm'));
                current.val(start.format('DD/MM/YYYY HH:mm'));
            });
        });
    }

    initDateRange() {
        $('.date-range-picker').each(function () {
            var current = $(this);
            var start_date = $('#' + current.attr('id') + '_start').val();
            var end_date = $('#' + current.attr('id') + '_end').val();
            var locale = $.extend(true, {}, daterangepickerLocale, {
                format: 'DD/MM/YYYY'
            });

            var options = {
                timePicker: false,
                showDropdowns: true,
                autoUpdateInput: true,
                showWeekNumbers: true,
                linkedCalendars: false,
                alwaysShowCalendars: true,
                locale: locale
            };

            if(start_date && end_date) {
                options.startDate =start_date;
                options.endDate = end_date;
            }

            current.daterangepicker(options , function(start, end, label) {
                $('#' + current.attr('id') + '_start').val(start.format('DD/MM/YYYY'));
                $('#' + current.attr('id') + '_end').val(end.format('DD/MM/YYYY'));
            });
        });
    }

    initDateTimeRange() {
        $('.date-time-range-picker').each(function () {
            var current = $(this);
            var start_date = $('#' + current.attr('id') + '_start').val();
            var end_date = $('#' + current.attr('id') + '_end').val();

            var options = {
                timePicker: true,
                timePicker24Hour: true,
                timePickerIncrement: 1,
                showDropdowns: true,
                autoUpdateInput: true,
                showWeekNumbers: true,
                linkedCalendars: false,
                alwaysShowCalendars: true,
                locale: daterangepickerLocale
            };

            if(start_date && end_date) {
                options.startDate =start_date;
                options.endDate = end_date;
            }

            current.daterangepicker(options , function(start, end, label) {
                $('#' + current.attr('id') + '_start').val(start.format('DD/MM/YYYY HH:mm'));
                $('#' + current.attr('id') + '_end').val(end.format('DD/MM/YYYY HH:mm'));
            });
        });
    }
}
