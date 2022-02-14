@extends('admin.messenger.template')

@if($title == "Inbox")
    @section('title', __("Inbox"))
@elseif($title == "Outbox")
    @section('title', __("Outbox"))
@else
    @section('title', __("Messages"))
@endif

@section('messenger-content')
@php
$countTopics = count($topics);
@endphp
<div class="box box-success">
    <div class="box-header no-padding">
        <div class="mailbox-controls">
            <!-- Check all button -->
            <!-- /.btn-group -->
            <button type="button" class="btn btn-warning btn-sm" onclick="location.reload();"><i class="fa fa-refresh"></i></button>
            <div class="pull-right">
                @if($countTopics > 0)
                    {{  $topics->firstItem() }}-{{ $topics->lastItem() }}/{{ $topics->total() }}
                    <div class="btn-group">
                        @if($topics->previousPageUrl())
                            @php
                            $previousPageUrl = $topics->previousPageUrl() . (request('type') ? '&type=' . request('type') : '');
                            @endphp
                            <a href="{{ $previousPageUrl }}" type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></a>
                        @endif
                        @if($topics->nextPageUrl())
                            @php
                                $nextPageUrl = $topics->nextPageUrl() . (request('type') ? '&type=' . request('type') : '');
                            @endphp
                            <a href="{{ $nextPageUrl }}" type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></a>
                        @endif

                    </div>
                 @endif
                <!-- /.btn-group -->
            </div>
            <!-- /.pull-right -->
        </div>
    </div>
    <div class="box-body no-padding">
        <div class="table-responsive mailbox-messages">
            <table class="table table-hover table-striped">
                <tbody>
                    @forelse($topics as $topic)
                        @php
                        $lastMessage = $topic->getLastMessageAsReceiver();
                        @endphp
                    <tr {!! $lastMessage && !$lastMessage->readByUser() ? 'class="not-read"' : '' !!}>
                        <td width="1%" class="mailbox-label"><i class="fa {{ $topic->type == 'direct' ? 'fa-user text-light-blue' : 'fa-question text-red' }}"></i></td>
                        <td width="1%">{!! $lastMessage && !$lastMessage->readByUser() ? '<small class="label pull-right bg-green new">new</small>' : ''!!}</td>
                        <td class="mailbox-name">
                            <a href="{{ route('admin.messenger.show', [$topic]) }}">
                                @switch($title)
                                    @case('Inbox')
                                        @if($topic->type == 'direct')
                                            {{ $lastMessage->sender->name }} {{ $lastMessage->sender->surname }}
                                        @else
                                            @if($lastMessage->receiver_model == 'App\Models\Admin\User')
                                                {{ $topic->roleReceiver->role_name }}
                                            @else
                                            {{ $lastMessage->sender->name }} {{ $lastMessage->sender->surname }}
                                            @endif
                                        @endif
                                    @break
                                    @case('Outbox')
                                        @if($topic->type == 'direct')
                                            {{ $topic->userReceiver->name }} {{ $topic->userReceiver->surname }}
                                        @else
                                            {{ $topic->roleReceiver->role_name }}
                                        @endif
                                    @break
                                @endswitch
                            </a>
                        </td>
                        <td class="mailbox-subject">{{ $topic->subject }}</td>
                        <td class="mailbox-attachment">{!! $lastMessage && !empty($lastMessage->getFirstMediaUrl('attachments')) ? '<i class="fa fa-paperclip"></i>' : ''  !!}</td>
                        <td class="mailbox-date">{{ $topic->sent_at->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">@lang('No entries in table')</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <!-- /.table -->
        </div>
        <!-- /.mail-box-messages -->
    </div>
    <!-- /.box-body -->
    <div class="box-footer no-padding">
        <div class="mailbox-controls">
            <!-- Check all button -->
            <!-- /.btn-group -->
            <button type="button" class="btn btn-warning btn-sm" onclick="location.reload();"><i class="fa fa-refresh"></i></button>
            <div class="pull-right">
                @if($countTopics > 0)
                    {{ $topics->firstItem() }}-{{ $topics->lastItem() }}/{{ $topics->total() }}
                    <div class="btn-group">
                        @if($topics->previousPageUrl())
                            <a href="{{ $previousPageUrl }}" type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></a>
                        @endif
                        @if($topics->nextPageUrl())
                            <a href="{{ $nextPageUrl }}" type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></a>
                        @endif
                </div>
                @endif
                <!-- /.btn-group -->
            </div>
            <!-- /.pull-right -->
        </div>
    </div>
</div>
<!-- /. box -->
@stop

@section('javascript')
@parent
<!-- Page Script -->
<script>
    $(function () {
        //Enable iCheck plugin for checkboxes
        //iCheck for checkbox and radio inputs
        $('.mailbox-messages input[type="checkbox"]').iCheck({
            checkboxClass: 'icheckbox_flat-blue',
            radioClass: 'iradio_flat-blue'
        });

        //Enable check and uncheck all functionality
        $(".checkbox-toggle").click(function () {
            var clicks = $(this).data('clicks');
            if (clicks) {
                //Uncheck all checkboxes
                $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
                $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
            } else {
                //Check all checkboxes
                $(".mailbox-messages input[type='checkbox']").iCheck("check");
                $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
            }
            $(this).data("clicks", !clicks);
        });

        $(".messenger-mass_destroy-button").on('click', function (evt) {
            var _that = $(this);
            evt.preventDefault();
            Swal.fire(_t('Are you sure you want to delete the selected messages?'), {
                buttons: {
                    cancel: {
                        text: _t('Cancel'),
                        value: null,
                        visible: true,
                        className: "btn btn-default"
                    },
                    catch: {
                        text: _t('Delete'),
                        className: "btn btn-danger",
                        value: "catch"
                    },
                },
                icon: "warning",
                dangerMode: true,
            })
                .then((value) => {
                    if(value == 'catch') {
                        var checkboxes = $('input[name="topic_id[]"]');
                        var topic_ids = checkboxes.filter(":checked").map(function () {
                            return this.value;
                        }).get();

                        $('input[name="topics"]').val(topic_ids);

                        _that.parent('.messenger-mass_destroy-form').submit();
                    }
                });

        })
    });
</script>
@stop
