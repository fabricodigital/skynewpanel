export default class DataTable {

    constructor() {
        this.init();
        this.hoverRow();
        this.deleteRow();
        this.restoreRow();
        this.export();
        this.initFilters();
        this.resetFilters();
        this.columnsVisibilityFilter();
        this.tableViewFilter();
    }

    config() {
        return {
            scrollY: '500px',
            scrollCollapse: true,
            mark: {
                filter: function(node){
                    var datatable = $(node.parentElement).closest('.dataTables_wrapper')
                    var datatableFilter = $('.datatable__filters th', datatable)
                        .eq($(node).closest("td").index())
                        .find('.datatable__filter');

                    if(
                        datatableFilter.hasClass('datatable__filter--select') ||
                        datatableFilter.hasClass('datatable__filter--select-multi') ||
                        datatableFilter.hasClass('datatable__filter--select-multi-ajax')
                    ){
                        return false;
                    }
                    return true;
                }
            },
            scrollX: true,
            language: {
                url: '/js/admin-panel/vendor/dataTables/lang/' + $("html").attr("lang") + '.json'
            },
            drawCallback: function( settings ) {
                $('.dt-button','.dataTables_wrapper').removeClass("dt-button");
            },
            ajax: {
                error: function (jqxhr, textStatus, thrownError) {
                    if(thrownError == 'Unauthorized' || jqxhr.status == 401 || jqxhr.status == 419) {
                        window.location.replace(routeLogin);
                    }
                }
            },
        }
    }

    init() {
        let dtObject = this;
        $('.ajaxDataTable').each(function () {
            dtObject.draw($(this));
        });

        $('.clientDataTable').each(function () {
            $(this).dataTable($.extend(true, {}, this.config(), {

            }));
        });

        $(document).on( 'init.dt', function ( e, settings ) {
            var api = new $.fn.dataTable.Api( settings );

            if (typeof window.route_mass_crud_entries_destroy != 'undefined') {
                $('.clientDataTable, .ajaxDataTable').siblings('.actions').html('<a href="' + window.route_mass_crud_entries_destroy + '" class="btn btn-xs btn-danger js-delete-selected" style="margin-top:0.755em;margin-left: 20px;">'+ _t('Delete selected') +'</a>');
            }

            var tableId = settings.sTableId;
            $("#" + tableId + "_filter input").unbind();
            $("#" + tableId + "_filter input").on('input', function(e, autoSelected) {
                if(autoSelected) {
                    api.search("");
                    return;
                }
                // If the length is 3 or more characters, or the user pressed ENTER, search
                if(this.value.length >= 3) {
                    // Call the API search function
                    api.search(this.value).draw();
                }
                // Ensure we clear the search if they backspace far enough
                if(this.value == "") {
                    api.search("").draw();
                }
                return;
            });
            $("#" + tableId + "_filter input").on('keyup', function(e, autoSelected) {
                if(autoSelected) {
                    api.search("");
                    return;
                }
                // If the length is 3 or more characters, or the user pressed ENTER, search
                if(e.keyCode == 13) {
                    // Call the API search function
                    api.search(this.value).draw();
                }
                return;
            });
        });

        $(document).on('expanded.pushMenu', function (evt) {
            dtObject.resize('.ajaxDataTable');
        })

        $(document).on('collapsed.pushMenu', function (evt) {
            dtObject.resize('.ajaxDataTable');
        })
    }

    hoverRow() {
        $(document).on( 'click', '.DTFC_LeftWrapper .dataTable tbody tr', function () {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
                $(this).closest('.dataTables_wrapper').find(".dataTables_scrollBody .dataTable tbody tr").eq($(this).index())
                    .removeClass('selected');
            } else {
                $(this).removeClass('selected');
                $(this).addClass('selected');
                $(this).closest('.dataTables_wrapper').find(".dataTables_scrollBody .dataTable tbody tr").eq($(this).index())
                    .addClass('selected');
            }
        });

        $(document).on( 'click', '.dataTables_scrollBody .dataTable tbody tr', function () {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
                $(this).closest('.dataTables_wrapper').find(".DTFC_LeftWrapper .dataTable tbody tr").eq($(this).index())
                    .removeClass('selected');
            }else {
                $(this).removeClass('selected');
                $(this).addClass('selected');
                $(this).closest('.dataTables_wrapper').find(".DTFC_LeftWrapper .dataTable tbody tr").eq($(this).index())
                    .addClass('selected');
            }
        });

        $(document).on('mouseover mouseout', '.DTFC_LeftWrapper .dataTable tbody tr', function () {
            if ($(this).hasClass('hovered')) {
                $(this).removeClass('hovered');
                $(this).closest('.dataTables_wrapper').find(".dataTables_scrollBody .dataTable tbody tr").eq($(this).index())
                    .removeClass('hovered');
            } else {
                $(this).removeClass('hovered');
                $(this).addClass('hovered');
                $(this).closest('.dataTables_wrapper').find(".dataTables_scrollBody .dataTable tbody tr").eq($(this).index())
                    .addClass('hovered');
            }
        });

        $(document).on('mouseover mouseout', '.dataTables_scrollBody .dataTable tbody tr', function () {
            if ($(this).hasClass('hovered')) {
                $(this).removeClass('hovered');
                $(this).closest('.dataTables_wrapper').find(".DTFC_LeftWrapper .dataTable tbody tr").eq($(this).index())
                    .removeClass('hovered');
            }else {
                $(this).removeClass('hovered');
                $(this).addClass('hovered');
                $(this).closest('.dataTables_wrapper').find(".DTFC_LeftWrapper .dataTable tbody tr").eq($(this).index())
                    .addClass('hovered');
            }
        });
    }

    deleteRow() {
        $(document).on('click', '.data-table-delete-single', function(evt) {
            evt.preventDefault();
            var form = $(this).parent('form');
            var canDeleteForever = $(this).data('can_delete_forever');
            var isDeleted = $(this).data('is_deleted');

            var swalConfig = {
                text: _t('Are you sure you want to delete the selected item?'),
                icon: "warning",
                buttonsStyling: false,
                showCancelButton: true,
                cancelButtonText: _t('Cancel'),
                confirmButtonText: _t('Delete'),
                denyButtonText: _t('Delete forever'),
                customClass: {
                    confirmButton: 'btn btn-warning',
                    denyButton: 'btn btn-danger',
                    cancelButton: 'btn btn-default'
                }
            };
            if (isDeleted) {
                swalConfig.showConfirmButton = false;
            }
            if (canDeleteForever) {
                swalConfig.showDenyButton = true;
            }

            Swal.fire(swalConfig)
                .then(function(result) {
                    if(result.isConfirmed) {
                        form.submit();
                    } else if (canDeleteForever && result.isDenied) {
                        Swal.fire({
                            text: _t('Are you sure you want to delete forever the selected item?'),
                            icon: "warning",
                            buttonsStyling: false,
                            showCancelButton: true,
                            cancelButtonText: _t('Cancel'),
                            confirmButtonText: _t('Delete forever'),
                            customClass: {
                                confirmButton: 'btn btn-danger',
                                cancelButton: 'btn btn-default',
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                form.find('.data-table-delete-single-forever').val(1);
                                form.submit();
                            }
                        });
                    }
                });
        });
    }

    restoreRow() {
        $(document).on('click', '.data-table-restore-single', function(evt) {
            evt.preventDefault();
            var form = $(this).parent('form');

            Swal.fire({
                    text: _t('Are you sure you want to restore the selected item?'),
                    icon: "warning",
                    buttonsStyling: false,
                    showCancelButton: true,
                    cancelButtonText: _t('Cancel'),
                    confirmButtonText: _t('Restore'),
                    input: 'checkbox',
                    inputPlaceholder: _t('Restore related records'),
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-default',
                    }
                })
                .then(function (result) {
                    if (result.isConfirmed) {
                        if($('#swal2-checkbox').prop('checked')) {
                            form.find('.data-table-restore-relations').val(1);
                        }
                        form.submit();
                    }
                });
        });
    }

    export() {
        $('.btn-export-dt').click(function(evt) {
            evt.preventDefault();
            let targetTable = $(this).data('target_table');

            let recordsDisplay = $('#' + targetTable).dataTable().api().page.info().recordsDisplay;

            if(recordsDisplay > 10000) {
                Swal.fire({
                    text: _t("The maximum allowed number of rows that can be exported is :numRows", {
                        numRows: 10000,
                    }),
                    icon: "warning",
                    showCancelButton: false,
                });

                return;
            }

            Swal.fire({
                text: _t('Are you sure you want to export the selected items?'),
                icon: "warning",
                buttonsStyling: false,
                showCancelButton: true,
                cancelButtonText: _t('Cancel'),
                confirmButtonText: _t('Yes'),
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-default',
                }
            }).then(function(result) {
                if(result.value) {
                    let params = $('#' + targetTable).dataTable().api().ajax.params();
                    let url = $('#' + targetTable).dataTable().api().ajax.url();
                    let visible = $('#' + targetTable).dataTable().api().columns().visible().toArray();
                    for (let column in params.columns) {
                        params.columns[column].visible = visible[column];
                    }

                    params.export = true;
                    params.length = -1;

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: params,
                        headers: {
                            'X-CSRF-TOKEN': window._token
                        },
                        success() {
                            Swal.fire({
                                html: _t("We've started exporting the selected items. You will receive an email notification when the export is complete.") + '<br/><br/><a href="/exports">' + _t('Go to Exports section') + '</a>',
                                icon: "success",
                                showCancelButton: false,
                            })
                        }
                    });
                }
            });
        }) ;
    }

    initFilters(container){
        var prefix = container ? container + ' ' : '';

        $(prefix + '.datatable__filter--select-multi').each(function () {
            let current = $(this);
            current.select2({
                language: $("html").attr("lang"),
                allowClear: true,
                cache: true,
                debug: true,
                placeholder: current.data('placeholder'),
            });
        });

        $(document).on('change', prefix + '.datatable__filter--select-multi', function (evt, autoSelected) {
            if(autoSelected) {
                return;
            }

            let current = $(this);
            let values = $(current).val();
            let tableId = $(current).data('table_target');
            let targetTable = $('#' + tableId);
            let columnTarget = $(current).data('column_target');

            targetTable.dataTable()
                .api()
                .columns( columnTarget )
                .search( values.join('|'), true, false )
                .draw();
        });


        $(prefix + '.datatable__filter--select-multi-ajax').each(function () {
            let current = $(this);
            current.select2({
                language: $("html").attr("lang"),
                dropdownCssClass: "select2-" + current.attr("name"),
                ajax: {
                    url: function (params) {
                        return current.attr('data-url');
                    },
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            needle: params.term, // search term
                        };
                    },
                    processResults: function (data) {
                        var selectobj = $.map(data, function (v) {
                            return {
                                'text': v[current.data('text_field')],
                                'id': v[current.data('id_field')] ? v[current.data('id_field')] : v['id'],
                            }
                        });
                        return { results: selectobj };
                    },
                    cache: true,
                    error: function (err) {

                    }
                },
                placeholder: current.data('placeholder'),
                minimumInputLength: 2,
                allowClear: true
            });
        });

        $(document).on('change', prefix + '.datatable__filter--select-multi-ajax', function (evt, autoSelected) {
            if(autoSelected) {
                return;
            }

            let current = $(this);
            let values = $(current).val();
            let tableId = $(current).data('table_target');
            let targetTable = $('#' + tableId);
            let columnTarget = $(current).data('column_target');

            targetTable.dataTable()
                .api()
                .columns( columnTarget )
                .search( values.join('|'), true, false )
                .draw();
        });

        $(prefix + '.datatable__filter--select').each(function () {
            let current = $(this);

            current.select2({
                language: $("html").attr("lang"),
                allowClear: true,
                cache: true,
                debug: true,
                placeholder: current.data('placeholder'),
            });
        });

        $(document).on('change', prefix + '.datatable__filter--select', function (evt, autoSelected) {

            if(autoSelected) {
                return;
            }

            let current = $(this);
            let value = $(current).val();
            let tableId = $(current).data('table_target');
            let targetTable = $('#' + tableId);
            let columnTarget = $(current).data('column_target');

            targetTable.dataTable()
                .api()
                .columns( columnTarget )
                .search( value )
                .draw();
        });

        $(prefix + '.datatable__filter--date-range-picker').each(function () {
            let current = $(this);
            let locale = $.extend(true, {}, daterangepickerLocale, {
                format: 'DD/MM/YYYY'
            });

            let options = {
                timePicker: false,
                showDropdowns: true,
                autoUpdateInput: false,
                showWeekNumbers: true,
                linkedCalendars: false,
                alwaysShowCalendars: true,
                locale: locale
            };

            current.daterangepicker(options);

            current.on('apply.daterangepicker', function (ev, picker) {
                $(this)
                    .val(picker.startDate.format(locale.format) + ' - ' + picker.endDate.format(locale.format))
                    .trigger('change');
            });

            current.on('cancel.daterangepicker', function (ev, picker) {
                $(this)
                    .val('')
                    .trigger('change');
            });
        });

        $(document).on('change', prefix + '.datatable__filter--date-range-picker', function (evt, autoSelected) {

            if(autoSelected) {
                return;
            }

            let current = $(this);
            let value = $(current).val();
            let tableId = $(current).data('table_target');
            let targetTable = $('#' + tableId);
            let columnTarget = $(current).data('column_target');

            targetTable.dataTable()
                .api()
                .columns( columnTarget )
                .search( value )
                .draw();
        });

        $(prefix + '.datatable__filter--datetime-range-picker').each(function () {
            let current = $(this);

            let options = {
                timePicker: true,
                timePicker24Hour: true,
                timePickerIncrement: 1,
                showDropdowns: true,
                autoUpdateInput: false,
                showWeekNumbers: true,
                linkedCalendars: false,
                alwaysShowCalendars: true,
                locale: daterangepickerLocale
            };

            current.daterangepicker(options);

            current.on('apply.daterangepicker', function (ev, picker) {
                $(this)
                    .val(picker.startDate.format(daterangepickerLocale.format) + ' - ' + picker.endDate.format(daterangepickerLocale.format))
                    .trigger('change');
            });

            current.on('cancel.daterangepicker', function (ev, picker) {
                $(this)
                    .val('')
                    .trigger('change');
            });
        });

        $(document).on('change', prefix + '.datatable__filter--datetime-range-picker', function (evt, autoSelected) {

            if(autoSelected) {
                return;
            }

            let current = $(this);
            let value = $(current).val();
            let tableId = $(current).data('table_target');
            let targetTable = $('#' + tableId);
            let columnTarget = $(current).data('column_target');

            targetTable.dataTable()
                .api()
                .columns( columnTarget )
                .search( value )
                .draw();
        });

        /**
         * Date picker
         */

        $(prefix + '.datatable__filter--date-picker').each(function () {
            let current = $(this);
            let locale = $.extend(true, {}, daterangepickerLocale, {
                format: 'DD/MM/YYYY'
            });

            let options = {
                timePicker: false,
                showDropdowns: true,
                autoUpdateInput: false,
                showWeekNumbers: true,
                linkedCalendars: false,
                alwaysShowCalendars: true,
                locale: locale,
                singleDatePicker: true,
            };

            current.daterangepicker(options);

            current.on('apply.daterangepicker', function (ev, picker) {
                $(this)
                    .val(picker.startDate.format(locale.format))
                    .trigger('change');
            });

            current.on('cancel.daterangepicker', function (ev, picker) {
                $(this)
                    .val('')
                    .trigger('change');
            });
        });

        $(document).on('change', prefix + '.datatable__filter--date-picker', function (evt, autoSelected) {

            if(autoSelected) {
                return;
            }

            let current = $(this);
            let value = $(current).val();
            let tableId = $(current).data('table_target');
            let targetTable = $('#' + tableId);
            let columnTarget = $(current).data('column_target');

            targetTable.dataTable()
                .api()
                .columns( columnTarget )
                .search( value )
                .draw();
        });

        /**
         * End date picker
         */

        /**
         * Datetime picker
         */

        $(prefix + '.datatable__filter--datetime-picker').each(function () {
            let current = $(this);
            let locale = $.extend(true, {}, daterangepickerLocale, {
                format: 'DD/MM/YYYY HH:mm'
            });

            let options = {
                timePicker: true,
                timePicker24Hour: true,
                timePickerIncrement: 1,
                showDropdowns: true,
                autoUpdateInput: false,
                showWeekNumbers: true,
                linkedCalendars: false,
                alwaysShowCalendars: true,
                locale: locale,
                singleDatePicker: true,
            };

            current.daterangepicker(options);

            current.on('apply.daterangepicker', function (ev, picker) {
                $(this)
                    .val(picker.startDate.format(locale.format))
                    .trigger('change');
            });

            current.on('cancel.daterangepicker', function (ev, picker) {
                $(this)
                    .val('')
                    .trigger('change');
            });
        });

        $(document).on('change', prefix + '.datatable__filter--datetime-picker', function (evt, autoSelected) {

            if(autoSelected) {
                return;
            }

            let current = $(this);
            let value = $(current).val();
            let tableId = $(current).data('table_target');
            let targetTable = $('#' + tableId);
            let columnTarget = $(current).data('column_target');

            targetTable.dataTable()
                .api()
                .columns( columnTarget )
                .search( value )
                .draw();
        });

        /**
         * End datetime picker
         */

        $(document).on('input', prefix + '.datatable__filter--search', function (evt, autoSelected) {
            if(autoSelected) {
                return;
            }

            let current = $(this);
            let value = $(current).val();
            let tableId = $(current).data('table_target');
            let targetTable = $('#' + tableId);
            let columnTarget = $(current).data('column_target');

            targetTable.dataTable()
                .api()
                .columns( columnTarget )
                .search( value )
                .draw();
        });

        $(document).ready(function () {
            $('body').on('input', '.datatable__filter--number-range', function (evt, autoSelected) {
                if(autoSelected) {
                    return;
                }

                let current = $(this);
                let targetTable = $('#' + $(current).data('table_target'));
                let columnTarget = $(current).data('column_target');
                let min;
                let max;

                if(current.hasClass('datatable__filter--number-range_from')) {
                    let maxInput = current.next('.datatable__filter--number-range_to');
                    min = current.val() ? current.val() : null;
                    max = maxInput.val() ? maxInput.val() : null;

                    maxInput.attr('min', min);
                }else{
                    let minInput = current.prev('.datatable__filter--number-range_from');
                    max = current.val() ? current.val() : null;
                    min = minInput.val() ? minInput.val() : null;

                    minInput.attr('max', max);
                }

                targetTable.dataTable()
                    .api()
                    .columns( columnTarget )
                    .search( min + "|" + max )
                    .draw();
            })
        });
    }

    resetFilters() {
        let dtObject = this;

        $('.btn-reset-filters-dt').on('click', function (evt) {
            evt.preventDefault();
            let tableID = $(this).data('target_table');
            let table = $('#' + tableID);

            localStorage.removeItem(tableID);

            dtObject.resetFiltersInputs(tableID);
            dtObject.resetColumnsVisibility(tableID);

            if ($.fn.DataTable.isDataTable(table)) {
                table.dataTable().fnDestroy();
            }

            dtObject.draw(table);
        });
    }

    resetFiltersInputs(tableID) {
        $('input.datatable__filter[data-table_target="' + tableID + '"]').each(function () {
            $(this).val('');
            $(this).trigger('input', true);
        });
        $('select.datatable__filter[data-table_target="' + tableID + '"]').each(function () {
            $(this).val('');
            $(this).trigger('change', true);
        });
        $('#' + tableID + '_filter input').each(function () {
            $(this).val('');
            $(this).trigger('input', true);
        });
    }

    resetColumnsVisibility(tableID) {
        let table = $('#' + tableID);
        let tableParams = JSON.parse(table.attr('data-params'));

        $(table).dataTable()
            .api()
            .columns()
            .visible(true, true);

        let checkboxes = $('.dt_col_visibility_filter[data-table_target="' + tableID + '"]');
        checkboxes.each(function () {
            let checkbox = $(this);
            $(tableParams.columns).each(function () {
                let column = this;
                if (checkbox.attr('id') == column.className) {
                    if(column.visible == undefined || column.visible) {
                        checkbox
                            .prop('checked', true)
                            .iCheck('update');
                    } else {
                        checkbox
                            .prop('checked', false)
                            .iCheck('update');
                    }
                }
            })
        })
    }

    toggleActions(tableId, disable) {
        $('.dt_save-view_btn[data-target_table="' + tableId + '"]').prop('disabled', disable);
        $('.dt_delete-view_btn[data-target_table="' + tableId + '"]').prop('disabled', disable);
        $('.select2-create_tag[data-target_table="' + tableId + '"]').prop('disabled', disable);
        $('button[data-target="#' + tableId + 'ColumnsVisibilityModal"]').prop('disabled', disable);
        $('button[data-target_table="' + tableId + '"]').prop('disabled', disable);
    }

    hideViewActions(tableId) {
        $('.dt_save-view_btn[data-target_table="' + tableId + '"]').hide();
        $('.dt_delete-view_btn[data-target_table="' + tableId + '"]').hide();
    }

    showViewActions(tableId) {
        $('.dt_save-view_btn[data-target_table="' + tableId + '"]').show();
        $('.dt_delete-view_btn[data-target_table="' + tableId + '"]').show();
    }

    toggleViewInfoMessage(tableId, display) {
        $('.datatable__view-info-message[data-target_table="' + tableId + '"]').css('display', display);
    }

    draw($table, calledFromTableView) {

        let tableId = $table.attr('id');
        let initParams = JSON.parse($table.attr('data-params'));
        let dtObject = this;

        //Transform order column names to column index. Can't be done in initParamsTransformed because order doesn't work on Reset Filters
        for (let i = 0; i < initParams.order.length; i++) {
            for (let j = 0; j < initParams.columns.length; j++) {
                if (initParams.order[i][0] == initParams.columns[j].data) {
                    initParams.order[i][0] = j.toString();
                }
            }
        }

        let ignoreLocalStorage = true;
        let initSearchCols = [];
        let initVisibleCols = [];
        let initParamsTransformed = {
            order: _.map(initParams.order, function (i) {
                return [
                    i[0].toString(),
                    i[1]
                ]
            }),
            search: null,
            searchCols: [],
            visibleCols: [],
        };

        calledFromTableView = calledFromTableView || false;

        initParams.processing = true;
        initParams.serverSide = true;

        for (let ic in initParams.columns) {
            let column = initParams.columns[ic];
            if(column.hasOwnProperty('search')) {
                initSearchCols.push(column.search);
            }else{
                initSearchCols.push(null);
            }

            initVisibleCols.push({
                data: column.data,
                column_target: '.dt_col_' + column.data,
                visible: column.hasOwnProperty('visible') && !column.visible ? 'false' : 'true',
            })
        }

        initParams.searchCols = initSearchCols;
        initParamsTransformed.visibleCols = initVisibleCols;
        initParamsTransformed.searchCols = initSearchCols;

        if(calledFromTableView && localStorage.hasOwnProperty(tableId)) {
            ignoreLocalStorage = false;
        }else if(localStorage.hasOwnProperty(tableId) && ( !initParams.hasOwnProperty('ignoreLocalStorage') || !initParams.ignoreLocalStorage)) {
            ignoreLocalStorage = false;
        }

        if(!ignoreLocalStorage ) {
            let savedParams = JSON.parse(localStorage.getItem(tableId));
            let visibleCols = savedParams.hasOwnProperty('visibleCols') ? savedParams.visibleCols : null;

            initParams.searchCols = savedParams.searchCols;
            initParams.order = savedParams.order;
            initParams.search = { "search": savedParams.search };

            if(visibleCols) {
                for (let c in initParams.columns) {
                    let visible = visibleCols.hasOwnProperty(c) ? visibleCols[c].visible : true;
                    initParams.columns[c].visible = visible == 'false' || visible == false ? false : true;
                }
            }
        }

        initParams.ajax.data.invisibleColumns = [];
        for (let ic in initParams.columns) {
            let column = initParams.columns[ic];
            if (column.hasOwnProperty('visible') && !column.visible) {
                initParams.ajax.data.invisibleColumns.push(column.data);
            }
        }

        let createdRowCallback = $table.data('created_row_callback');

        initParams = $.extend(true, {}, initParams, {
            createdRow: function (row, data, dataIndex) {
                if ($(data.deleted).data('is_deleted')) {
                    $(row).addClass('record-deleted');
                    $('td', row).addClass('record-deleted');
                }

                if(createdRowCallback) {
                    dtObject.onCreatedRow(createdRowCallback, row, data, dataIndex);
                }
            }
        });

        let params =  $.extend(true, {}, this.config(), initParams);

        let table = $table.DataTable(params);

        table.on('preDraw', function () {
            dtObject.toggleActions(tableId, true);
        });

        table.on('draw', function (evt) {
            let api = $(this).dataTable().api();
            let params = api.ajax.params();
            let visible = api.columns().visible().toArray();

            // To been assigned in localstorage obj.
            let searchCols = [];
            let orderCols = [];
            let visibleCols = [];
            let search = null;

            for(let c in params.columns) {
                let column = params.columns[c];
                let column_target = '.dt_col_' + column.data;
                //Setup search pattern for each column
                if(column.hasOwnProperty('search') && column.search.hasOwnProperty('value') && column.search.value) {

                    // Search filer
                    let searchFilter = $('input.datatable__filter--search[data-column_target="' + column_target + '"][data-table_target="' + tableId  + '"]');
                    // Select filter
                    let selectFilter = $('select.datatable__filter--select[data-column_target="' + column_target + '"][data-table_target="' + tableId  + '"]');
                    // Multi select filter
                    let multiSelectFilter = $('select.datatable__filter--select-multi[data-column_target="' + column_target + '"][data-table_target="' + tableId  + '"]');
                    // Multi select filter
                    let multiSelectAjaxFilter = $('select.datatable__filter--select-multi-ajax[data-column_target="' + column_target + '"][data-table_target="' + tableId  + '"]');
                    // Date Range Picker filter
                    let dateRangePickerFilter = $('input.datatable__filter--date-range-picker[data-column_target="' + column_target + '"][data-table_target="' + tableId  + '"]');
                    // DateTime Range Picker filter
                    let datetimeRangePickerFilter = $('input.datatable__filter--datetime-range-picker[data-column_target="' + column_target + '"][data-table_target="' + tableId  + '"]');
                    // Date Picker filter
                    let datePickerFilter = $('input.datatable__filter--date-picker[data-column_target="' + column_target + '"][data-table_target="' + tableId  + '"]');
                    // DateTime Picker filter
                    let datetimePickerFilter = $('input.datatable__filter--datetime-picker[data-column_target="' + column_target + '"][data-table_target="' + tableId  + '"]');
                    //Number Range From Picker
                    let numberRangeFrom = $('input.datatable__filter--number-range_from[data-column_target="' + column_target + '"][data-table_target="' + tableId  + '"]');
                    //Number Range To Picker
                    let numberRangeTo = $('input.datatable__filter--number-range_to[data-column_target="' + column_target + '"][data-table_target="' + tableId  + '"]');

                    if(searchFilter.length) {
                        $(searchFilter).val(column.search.value);
                    }

                    if(selectFilter.length) {
                        $(selectFilter).val(column.search.value);
                        $(selectFilter).trigger('change', true);
                    }

                    if(multiSelectFilter.length) {
                        let valuesToArr = column.search.value.split('|');
                        $(multiSelectFilter).val(valuesToArr);
                        $(multiSelectFilter).trigger('change', true);
                    }
                    if(multiSelectAjaxFilter.length) {
                        let valuesToArr = column.search.value.split('|');
                        $.each(valuesToArr, function (i, value) {
                            if(!multiSelectAjaxFilter.find("option[value='" + value + "']").length) {
                                let newOption = new Option(value, value, true, true);
                                multiSelectAjaxFilter.append(newOption);
                            }
                        });
                        $(multiSelectAjaxFilter).val(valuesToArr);
                        $(multiSelectAjaxFilter).trigger('change', true);
                    }
                    if(dateRangePickerFilter.length) {
                        $(datetimeRangePickerFilter).val(column.search.value);
                    }
                    if(datetimeRangePickerFilter.length) {
                        $(datetimeRangePickerFilter).val(column.search.value);
                    }
                    if(datePickerFilter.length) {
                        $(datePickerFilter).val(column.search.value);
                    }
                    if(datetimePickerFilter.length) {
                        $(datetimePickerFilter).val(column.search.value);
                    }
                    if(numberRangeFrom.length) {
                        let numberRangeFromValuesToArr = column.search.value.split('|');
                        let numberRangeFromValue = numberRangeFromValuesToArr.hasOwnProperty(0)
                        && parseInt(numberRangeFromValuesToArr[0])
                            ? parseInt(numberRangeFromValuesToArr[0])
                            : null;

                        $(numberRangeFrom).val(numberRangeFromValue);
                    }

                    if(numberRangeTo.length) {
                        let numberRangeToValuesToArr = column.search.value.split('|');
                        let numberRangeToValue = numberRangeToValuesToArr.hasOwnProperty(1)
                        && parseInt(numberRangeToValuesToArr[1])
                            ? parseInt(numberRangeToValuesToArr[1])
                            : null;

                        $(numberRangeTo).val(numberRangeToValue);
                    }

                    // Assign search value for local storage object
                    searchCols[c] = {search: column.search.value};
                }else {
                    // Set search value to null for local storage object
                    searchCols[c] = null;
                }

                //Assign visibility of the column for local storage object
                visibleCols[c] = {
                    data: column.data,
                    column_target: column_target,
                    visible: visible[c] ? 'true' : 'false',
                };
            }

            if(params.hasOwnProperty('order')) {
                for(let o in params.order) {
                    let order = params.order[o];
                    if(order.hasOwnProperty('column') && order.hasOwnProperty('dir')) {
                        orderCols.push([order.column.toString(), order.dir]);
                    }
                }
            }

            if(params.hasOwnProperty('search')) {
                search = $.trim(params.search.value) == '' ? null : params.search.value;
            }

            let savedParams = localStorage.hasOwnProperty(tableId) ? JSON.parse(localStorage.getItem(tableId)) : {};
            let newParams = $.extend(true, {}, savedParams, {
                searchCols: searchCols,
                order: orderCols,
                search: search,
                visibleCols: visibleCols
            });
            newParams.order = orderCols;

            localStorage.setItem(tableId, JSON.stringify(newParams));

            dtObject.toggleActions(tableId, false);

            let paramsIsEqual = _.isEqual(
                _.omit(newParams, ['last_used_view']),
                _.omit(initParamsTransformed, ['last_used_view']),
            );

            if(!paramsIsEqual) {
                $('.' + tableId + 'FiltersAlert').css('display', 'block');
                dtObject.toggleViewInfoMessage(tableId, 'block');
            }else{
                $('.' + tableId + 'FiltersAlert').css('display', 'none');
                dtObject.toggleViewInfoMessage(tableId, 'none');
            }

            if(!breakpoint("xs")) {
                setTimeout(function () {
                    dtObject.fixedColumns(tableId, 2);
                }, 0);
            }
        });
    }

    fixedColumns(tableId, fixedCount) {
        let tableData = $('#' + tableId);
        let tableHeader = $('#' + tableId).closest('.dataTables_scroll').find('.dataTables_scrollHead table');

        let leftWidth = 0;
        for (let i = 1; i <= fixedCount; i++) {
            tableHeader.find('th:nth-child(' + i + ')').each(function () {
                let cell = $(this);

                cell.css('position', 'sticky');
                cell.css('left', leftWidth);
                cell.css('background-color', '#ffffff !important');
                cell.css('z-index', 1);
            });

            tableData.find('td:nth-child(' + i + ')').each(function () {
                let cell = $(this);
                let row = cell.closest('tr')

                cell.css('position', 'sticky');
                cell.css('left', leftWidth);
                let backColor = row.css('background-color');
                if (backColor != "rgb(249, 249, 249)") {
                    backColor = '#ffffff'
                }
                cell.css('background-color', backColor);
                cell.css('z-index', 1);

                row.hover(function () {
                    cell.css('background-color', '#dff0d8');
                },function () {
                    cell.css('background-color', backColor);
                })

                row.click(function () {
                    if (cell.has('record-deleted')) {
                        return ;
                    }
                    if (cell.hasClass('selected')) {
                        cell.removeClass('selected');
                    } else {
                        cell.addClass('selected')
                    }
                });
            });

            leftWidth = leftWidth + 1 + tableHeader.find('th:nth-child(' + i + '):first').width();
        }
    }

    columnsVisibilityFilter() {

        let dtColVisibilityFilters = $('.dt_col_visibility_filter');

        $(dtColVisibilityFilters).on('ifCreated', function () {
            let tableId = $(this).data('table_target');
            let column_target = $(this).data('column_target');
            let initTableParams = $('#' + tableId).data('params');

            if(initTableParams.hasOwnProperty('ignoreLocalStorage') && initTableParams.ignoreLocalStorage == true) {
                for (let c in initTableParams.columns) {
                    let column = initTableParams.columns[c];

                    if(('.dt_col_' + column.data) != column_target) {
                        continue;
                    }

                    if(column.hasOwnProperty('visible') && column.visible == false) {
                        $(this).prop('checked', false).iCheck('update');
                    }else{
                        $(this).prop('checked', true).iCheck('update');
                    }

                    break;
                }

                return;
            }

            let savedParams = localStorage.hasOwnProperty(tableId) ? JSON.parse(localStorage.getItem(tableId)) : null;

            if(!savedParams || !savedParams.hasOwnProperty('visibleCols')) {
                return;
            }

            for (let c in savedParams.visibleCols) {
                let isVisible = savedParams.visibleCols[c].visible;

                if(savedParams.visibleCols[c].column_target != column_target) {
                    continue;
                }

                if(isVisible == 'false' || !isVisible) {
                    $(this).prop('checked', false).iCheck('update');
                }else{
                    $(this).prop('checked', true).iCheck('update');
                }
                break;
            }
        });

        $(dtColVisibilityFilters).iCheck({
            checkboxClass: 'icheckbox_square-green',
            increaseArea: '50%' // optional
        });

        $('.select_all_dt_column_visibility').click(function () {
            let tableTarget = $(this).data('table_target');
            $('.dt_col_visibility_filter[data-table_target="' + tableTarget + '"]').prop('checked', true).iCheck('update');
        });

        $('.unselect_all_dt_column_visibility').click(function () {
            let tableTarget = $(this).data('table_target');
            $('.dt_col_visibility_filter[data-table_target="' + tableTarget + '"]').prop('checked', false).iCheck('update');
        });

        $('body').on('click', '.apply_dt_column_visibility', function (evt) {
            evt.preventDefault();

            let tableTarget = $(this).data('table_target');
            let checkBoxes = $('.dt_col_visibility_filter[data-table_target="' + tableTarget + '"]');
            let savedParams = localStorage.hasOwnProperty(tableTarget) ? JSON.parse(localStorage.getItem(tableTarget)) : {};
            let newParams = {
                searchCols: [],
                order: [],
                visibleCols: [],
                last_used_view: savedParams.hasOwnProperty('last_used_view') ? savedParams.last_used_view : null,
            };
            let newIndex = 0;
            let selectedView = $('select[name="view"][data-target_table="' + tableTarget + '"]').val();
            let form = $(this).parent('form');

            checkBoxes.each(function () {
                let visible = $(this).is(':checked');
                let columnTarget = '.dt_col_' + $(this).data('column_target');
                let savedIndex = null;

                _.forEach(savedParams.visibleCols, function (sVC, index) {
                    if(sVC.column_target === columnTarget) {
                        savedIndex = index;
                        let tmpVC = sVC;
                        tmpVC.visible = visible ? "true" : "false";
                        newParams.visibleCols.push(tmpVC);
                        newParams.searchCols.push(savedParams.searchCols[index]);
                        return false;
                    }
                });

                _.forEach(savedParams.order, function (sO, index) {
                    if(sO[0] == savedIndex) {
                        newParams.order.push([newIndex.toString(), sO[1]]);
                    }
                });

                newIndex++;
            });

            localStorage.setItem(tableTarget, JSON.stringify(newParams));

            form.find('input[name="target_table"]').val(tableTarget);
            form.find('input[name="table_params"]').val(JSON.stringify(newParams));
            form.find('input[name="view_name"]').val(selectedView);
            form.submit();
        })
    }

    tableViewFilter() {
        let dtObject = this;
        let select2CreateTag = $('.select2-create_tag');
        let saveBtns = $('.dt_save-view_btn');
        let deleteBtns = $('.dt_delete-view_btn');

        select2CreateTag.each(function () {
            let current = $(this);

            current.select2({
                tags: true,
                allowClear: false,
                placeholder: current.data('placeholder'),
                createTag: function (params) {
                    if(current.val() == 'Default' && (params.term == '' || !params.term)) {
                        dtObject.hideViewActions(current.data('target_table'));
                    }else{
                        dtObject.showViewActions(current.data('target_table'));
                    }

                    return {
                        id: params.term,
                        text: params.term + " (" + _t('New') + ")",
                    }
                }
            });
        });

        select2CreateTag.on('change', function (evt, autoTriggered) {
            evt.preventDefault();
            if(autoTriggered) {
                return;
            }
            let selected = $(this).find(":selected");
            let selectedVal = selected.val();
            let selectedText = selected.text();

            if(selectedVal !== 'Default' && selectedVal !== selectedText) {
                return;
            }

            let form = $(this).closest('form');
            let targetTable = $(this).data('target_table');
            let savedParams = localStorage.hasOwnProperty(targetTable) ? JSON.parse(localStorage.getItem(targetTable)) : null;
            form.find('input[name="table_params"]').val(JSON.stringify(savedParams));
            localStorage.removeItem(targetTable);

            form.submit();
        });

        saveBtns.on('click', function (evt) {
            evt.preventDefault();

            let current = $(this);
            let selectTargetID = current.data('target_select');
            let selectTarget = $('#' + selectTargetID);
            let viewName = selectTarget.val();
            let form = current.closest('form');
            let targetTable = current.data('table_target');
            let savedParams = localStorage.hasOwnProperty(targetTable) ? JSON.parse(localStorage.getItem(targetTable)) : {};
            form.find('input[name="view_name"]').val(viewName);
            form.find('input[name="table_params"]').val(JSON.stringify(savedParams));

            form.submit();
        });

        deleteBtns.on('click', function (evt) {
            evt.preventDefault();

            let current = $(this);

            Swal.fire({
                text: _t('Are you sure you want to remove the selected table view?'),
                icon: "warning",
                buttonsStyling: false,
                showCancelButton: true,
                cancelButtonText: _t('Cancel'),
                confirmButtonText: _t('Delete'),
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-default',
                }
            }).then(function (result) {
                if(result.value) {
                    let selectTargetID = current.data('target_select');
                    let selectTarget = $('#' + selectTargetID);
                    let viewName = selectTarget.val();
                    let form = current.closest('form');
                    let targetTable = current.data('table_target');
                    form.find('input[name="view_name"]').val(viewName);
                    localStorage.removeItem(targetTable);

                    form.submit();
                }
            });
        })
    }

    onCreatedRow(property, row, data, dataIndex) {

    }

    resize(selector) {
        let dtTables = $(selector);
        if(!dtTables.length) {
            return;
        }

        let tableIds = [];
        dtTables.each(function () {
            let tableId = $(this).attr('id');
            /**
             * Datatables make a copy of table,
             * this prevent resize to be called twice for same table
             */
            if(tableId && tableId !== undefined) {
                tableIds.push(tableId);
            }
        });

        _.forEach(tableIds, function (tableId) {
            let table = $('#' + tableId);

            table.dataTable().api().columns.adjust();
            table.dataTable().api().draw();
        });
    }
}
