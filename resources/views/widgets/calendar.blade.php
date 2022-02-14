<div class="box box-success widget widget--calendar" id="widgets.calendar">
    <div class="box-header with-border">
        <h3 class="box-title">@lang('Calendar')</h3>

        @if(!empty($draggable))
        <div class="box-tools pull-right">
            <a href="javascript:void(0)" class="btn gs-drag"><i class="fa fa-arrows"></i></a>
        </div>
        @endif
    </div>
    <div class="box-body no-padding">
        <div id='calendar'></div>
    </div>
</div>

@can('create', \App\Models\Admin\Event::class)
    @include('admin.event.partials._form')
@else
    @include('admin.event.show')
@endcan

@section('javascript')
    @parent
    <script>
        $(document).ready(function () {
            @can('create', \App\Models\Admin\Event::class)
                var eventModal = $('#event-form-modal');
                var description  = eventModal.find('#description');
                description.summernote(window.editorDefaultConfig);

            @else
                var eventModal = $('#event-show-modal');
            @endcan
            var Error = new window.Error;
            var clickedEvent = {};
            var roles = {!! DB::table('roles_trans')->where('locale', app()->getLocale())->get(['role_name as name', 'role_id as id']) !!};

            // Init calendar
            $('#calendar').fullCalendar({
                header    : {
                    left  : 'prev,next today',
                    center: 'title',
                    right : 'month,agendaWeek,agendaDay'
                },
                buttonText: {
                    today: _t('today'),
                    month: _t('month'),
                    week : _t('week'),
                    day  : _t('day')
                },
                eventStartEditable: false,
                locale: '{{ Auth::user()->locale }}',
                timezone: '{{ config("app.timezone") }}',
                events: function (start, end, timezone, callback) {
                    $.ajax({
                        url: '{{ route('admin.event.index') }}',
                        dataType: 'json',
                        data: {
                            start: start.unix(),
                            end: end.unix()
                        },
                        success: function (res) {
                            callback(res.events);
                        },
                        error: function (err) {

                        }
                    })
                },
                displayEventTime: false,
                displayEventEnd: false,
                editable: true,
                @can('create', \App\Models\Admin\Event::class)
                dayClick: function(date, event, view) {
                    Error.remove().hide();
                    eventModal.find('.modal-revisions').html('');

                    var titleLabel = _t('New event');
                    var createLabel = _t('Save');
                    var startDateTime = date.format('DD/MM/YYYY HH:mm');
                    var endDateTime = date.endOf('day').format('DD/MM/YYYY HH:mm');

                    eventModal.find('#event_action_button').text(createLabel);
                    eventModal.find('.modal-title').text(titleLabel);
                    eventModal.find('#duration').daterangepicker({
                        timePicker: true,
                        timePicker24Hour: true,
                        timePickerIncrement: 30,
                        locale: daterangepickerLocale,
                        startDate: startDateTime,
                        endDate: endDateTime,
                    }, function(start, end, label) {
                        eventModal.find('#start').val(start.format('DD/MM/YYYY HH:mm'));
                        eventModal.find('#end').val(end.format('DD/MM/YYYY HH:mm'));
                    });
                    eventModal.find('#title').val('');
                    eventModal.find('#start').val(startDateTime);
                    eventModal.find('#end').val(endDateTime);
                    eventModal.find('#color').val('');
                    eventModal.find('#color-selector').colorselector({
                        callback: function (value, color, title) {
                            $("#color").val(color);
                        }
                    });
                    eventModal.find('#color-selector').colorselector('setColor',"#3c8dbc");

                    var rolesSelect = eventModal.find('#roles');
                    rolesSelect.html("");

                    for (var role in roles) {
                        var newRoleOption = new Option(roles[role].name, roles[role].id, false, false);
                        eventModal.find('#roles').append(newRoleOption);
                    }

                    eventModal.find('#users').html("");
                    eventModal.find('#description').summernote('code', event.description ? event.description : '');
                    eventModal.find('#event_delete_button').hide();
                    eventModal.find('.attachments-preview-container').html("");
                    eventModal.find('input[name="attachments[]"]').val('');
                    eventModal.find('.mailbox-attachments').html("");
                    eventModal.find('.attachments-container').hide();

                    eventModal.data('end_point', '{{ route('admin.event.store') }}');
                    eventModal.data('submit_method', 'POST');
                    eventModal.data('event_action', 'create');
                    eventModal.modal();
                },
                eventClick: function(event) {
                    clickedEvent = event;
                    Error.remove().hide();

                    var titleLabel = event.title;
                    var updateLabel = _t('Save');
                    var startDateTime = event.start.format('DD/MM/YYYY HH:mm');
                    var endDateTime = event.end.format('DD/MM/YYYY HH:mm');

                    eventModal.find('#event_action_button').text(updateLabel);
                    eventModal.find('.modal-title').text(titleLabel);
                    eventModal.find('#title').val(event.title);

                    eventModal.find('#duration').daterangepicker({
                        timePicker: true,
                        timePicker24Hour: true,
                        timePickerIncrement: 30,
                        locale: daterangepickerLocale,
                        startDate: startDateTime,
                        endDate: endDateTime,
                    }, function(start, end, label) {
                        eventModal.find('#start').val(start.format('DD/MM/YYYY HH:mm'));
                        eventModal.find('#end').val(end.format('DD/MM/YYYY HH:mm'));
                    });

                    eventModal.find('#start').val(startDateTime);
                    eventModal.find('#end').val(endDateTime);
                    eventModal.find('#color').val(event.color);
                    eventModal.find('#color-selector').colorselector({
                        callback: function (value, color, title) {
                            $("#color").val(value);
                        }
                    });
                    eventModal.find('#color-selector').colorselector('setColor', event.color);
                    eventModal.find('#event_delete_button').show();
                    eventModal.find('#description').summernote('code', event.description ? event.description : '');

                    eventModal.find('#roles').html("");
                    for(var role in roles) {
                        var selected = false;
                        for(eventRole in event.roles) {
                            if(event.roles[eventRole].id == roles[role].id) {
                                selected = true;
                                break;
                            }
                        }
                        var newRoleOption = new Option(roles[role].name, roles[role].id, selected, selected);
                        eventModal.find('#roles').append(newRoleOption);
                    }
                    eventModal.find('#roles').trigger('change');

                    eventModal.find('#users').html("");
                    for(i in event.users) {
                        var user = event.users[i];
                        var newOption = new Option(user.name + ' ' + user.surname, user.id, true, true);
                        eventModal.find('#users').append(newOption);
                    }
                    eventModal.find('#users').trigger('change');

                    var attachmentsContainer = eventModal.find('.event-attachments-container');
                    attachmentsContainer.html("");
                    attachmentsContainer.html(event.media_template);

                    eventModal.data('end_point', '{{ route('admin.event.store') }}' + '/' + event.id);
                    eventModal.data('submit_method', '{{ 'POST' }}');
                    eventModal.data('event_action', 'update');
                    eventModal.find('.attachments-preview-container').html("");
                    eventModal.find('input[name="attachments[]"]').val('');

                    if(!event.read) {
                        readEvent(event, function (event) {
                            Event.$emit('eventRead');
                            event.read = true;
                            $('#calendar').fullCalendar('updateEvent', event);
                        });
                    }

                    eventModal.modal();

                    eventModal.find('.modal-revisions')
                        .html('')
                        .html(generateRevisionsDataTalbeHTML());

                    $('.ajaxDataTableCalendar').dataTable({
                        language: {
                            url: '/js/admin-panel/vendor/dataTables/lang/' + $("html").attr("lang") + '.json'
                        },
                        processing: true,
                        serverSide: true,
                        order: $(this).data('order') ? $(this).data('order') : [ [0, 'asc'] ],
                        ajax: {
                            method: 'POST',
                            url:  event.datatables_revisions_route,
                            data: {
                                '_token': '{{ csrf_token() }}',
                            }
                        },
                        columns: $(this).data('columns'),
                        drawCallback: function( settings ) {
                            $('.dt-button','.dataTables_wrapper').removeClass("dt-button");
                        }
                    });
                },
                @else
                eventClick: function (event) {
                    clickedEvent = event;
                    eventModal.find('.modal-title').text(event.title);

                    eventModal.find('.event-start').text(event.start.format('DD/MM/YYYY HH:mm'));
                    eventModal.find('.event-end').text(event.start.format('DD/MM/YYYY HH:mm'));
                    eventModal.find('.event-description').html(event.description);

                    var attachmentsContainer = eventModal.find('.event-attachments-container');
                    attachmentsContainer.html("");
                    attachmentsContainer.html(event.media_template);

                    eventModal.modal();

                    if(!event.read) {
                        readEvent(event, function (event) {
                            Event.$emit('eventRead');
                            event.read = true;
                            $('#calendar').fullCalendar('updateEvent', event);
                        });
                    }

                },
                @endcan
            });

            function readEvent(event, callback) {

                $.ajax({
                    url: "{{ route('admin.event.index') }}" + "/" + event.id + "/read",
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (res) {
                        callback(event);
                    },
                    error: function (err) {

                    }
                })
            }

            @can('create', \App\Models\Admin\Event::class)
            // Send AJAX request on action
            var eventActionButton = eventModal.find('#event_action_button');
            eventActionButton.on('click', function () {
                Error.remove().hide();

                var formData = new FormData();
                formData.append('title', eventModal.find('#title').val());
                formData.append('start', eventModal.find('#start').val());
                formData.append('end', eventModal.find('#end').val());
                formData.append('color', eventModal.find('#color').val());
                formData.append('description', eventModal.find('#description').val());

                var roles = eventModal.find('#roles').val();
                for (var role in roles) {
                    formData.append('roles[]', roles[role]);
                }

                var users = eventModal.find('#users').val();
                for (var user in users) {
                    formData.append('users[]', users[user]);
                }

                var attachments = $('input[name="attachments[]"]')[0].files;
                for (var attachment in attachments) {
                    formData.append('attachments[]', attachments[attachment]);
                }

                $.ajax({
                    url: eventModal.data('end_point'),
                    dataType: 'json',
                    method: eventModal.data('submit_method'),
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (res) {
                        $.extend(clickedEvent, res.event);
                        switch (eventModal.data('event_action')) {
                            case 'create':
                                $('#calendar').fullCalendar( 'renderEvent', clickedEvent, true);
                                break;
                            case 'update':
                                $('#calendar').fullCalendar('updateEvent', clickedEvent);
                                break;
                        }
                        eventModal.modal('hide');
                    },
                    error: function (err) {
                        if(err.hasOwnProperty('responseJSON') && err.responseJSON.hasOwnProperty('errors') ) {
                            Error.set(err.responseJSON.errors).show();
                        }
                    }
                })
            });

            $('#event_delete_button').on('click', function () {
                Swal.fire({
                    text: _t('Are you sure you want to delete') + " " + eventModal.find('#title').val() + "?",
                    icon: "warning",
                    buttonsStyling: false,
                    showCancelButton: true,
                    cancelButtonText: _t('Cancel'),
                    confirmButtonText: _t('Delete'),
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-default',
                    }
                })
                    .then(function (result) {
                        if(result.value) {
                            $.ajax({
                                url: eventModal.data('end_point'),
                                dataType: 'json',
                                method: 'DELETE',
                                data: {
                                    _token: eventModal.find('input[name="_token"]').val(),
                                },
                                success: function (res) {
                                    $.extend(clickedEvent, res.event);
                                    eventModal.modal('hide');
                                    $('#calendar').fullCalendar('removeEvents', [clickedEvent.id]);
                                },
                                error: function (err) {

                                }
                            });
                        }
                    });
            });
            @endcan

            function generateRevisionsDataTalbeHTML() {

                @php
                    $dataTableColumns = [
                        [
                            'data' => 'created_at'
                        ],
                        [
                            'data' => 'type',
                        ],
                        [
                            'data' => 'user'
                        ],
                        [
                            'data' => 'old'
                        ],
                        [
                            'data' => 'new'
                        ],
                        [
                            'data' => 'ip'
                        ],
                    ];

                    $dataTableOrder = [ [0, 'desc'] ];
                @endphp
                var html = '<div class="panel panel-default">';
                html += '<div class="panel-heading">';
                html += '<h3 class="panel-title">{{ __('Audit log') }}</h3>';
                html += '</div>';
                html += '<div class="panel-body">';
                html += '<table class="ajaxDataTableCalendar table table-bordered table-striped table-responsive nowrap" data-columns="{{ json_encode($dataTableColumns) }}" data-order="{{ json_encode($dataTableOrder) }}" data-page-length="25">'
                html += '<thead>';
                html += '<tr>';
                html += '<th>{{ __('Created') }}</th>';
                html += '<th>{{ __('Action') }}</th>';
                html += '<th>{{ __('User') }}</th>';
                html += '<th>{{ __('Old') }}</th>';
                html += '<th>{{ __('New') }}</th>';
                html += '<th>{{ __('IP') }}</th>';
                html += '</tr>';
                html += '</thead>';
                html += '</table>';
                html += '</div>';
                html += '</div>';

                return html;
            }
        });
    </script>
@stop

