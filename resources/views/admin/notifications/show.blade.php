@extends('layouts.admin')

@section('title', $notification::getMsgTrans('view_heading'))

@section('content')
    @include('partials._content-heading', ['title' => $notification::getMsgTrans('view_heading'), 'deleted' => !empty($notification->deleted_at) ? true : false])

    <div class="panel panel-default{{ !empty($notification->deleted_at) ? ' record-deleted' : '' }}">
        <div class="panel-heading">
            <h3>
                {{ $notification->title }}
                <span class="mailbox-read-time pull-right">
                    <time datetime="{{ $notification->start }}">{{ $notification->start->format('d/m/Y H:i') }}</time>
                    -
                    <time datetime="{{ $notification->end }}">{{ $notification->end->format('d/m/Y H:i') }}</time>
                </span>
            </h3>
        </div>
        <div class="panel-body">
            {!! $notification->text !!}
            @include('partials._attachments', ['item' => $notification])
        </div>
        <div class="panel-footer">
            <a href="{{ route('admin.notifications.index') }}" class="btn btn-default">@lang('Back')</a>
        </div>
    </div>

    <div class="pull-left">
        @include('partials._restore-action-btn', ['model' => $notification, 'routeNamespace' => 'notifications'])
    </div>

    <div class="pull-right">
        <a href="{{ route('admin.notifications.index') }}" class="btn btn-primary">@lang('Back')</a>
    </div>

    <div class="clearfix"></div>

    @can('view_all', \App\Models\Admin\Revision::class)
        @include('admin.datatables._datatable-secondary', [
            'dataTableObject' => $revisionsDataTableObject,
            'permissionClass' => \App\Models\Admin\Revision::class,
            'title' => \App\Models\Admin\Revision::getTitleTrans(),
            'disableColumnsSelect' => true
        ])
    @endcan

    @php
        $readNotifications = Auth::user()->read_notifications ? array_pluck(Auth::user()->read_notifications, 'id') : [];
        $isNotificationReadByUser = in_array($notification->id, $readNotifications);
    @endphp
    @if(!$isNotificationReadByUser)
        <make-notification-as-read :route="'{{ route('admin.ajax.notifications.read', [$notification]) }}'"></make-notification-as-read>
    @endif
@stop

