@extends('admin.messenger.template')

@section('title', __('Conversation'))

@section('messenger-content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3>{{ $topic->subject }}</h3>
        </div>
        <div class="box-body">
            {{ html()->form('PUT', route('admin.messenger.update', [$topic]))->class('')->acceptsFiles()->open() }}
            <div class="form-group">
                {{ html()->textarea('content')->id('content')->class('form-control editor') }}
            </div>
            <div class="form-group">
                <ul class="list-group preview-container attachments-preview-container">

                </ul>
                @include('partials.inputs._file-upload', ['name' => 'attachments[]', 'label' => __('Attachments'), 'previewContainer' => 'attachments-preview-container', 'multiple' => true])
            </div>
            <div class="form-group">
                <a href="{{ route('admin.messenger.index') }}" class="btn btn-default">
                    <i class="fa fa-times"></i> @lang('Discard')
                </a>
                <div class="pull-right">
                    {{ html()->button('<i class="fa fa-reply"></i> '.__('Reply'))->type('submit')->class('btn btn-success') }}
                </div>
            </div>
            <div class="box-group form-group" id="accordion">
                @foreach($topic->messages as $message)
                    <div class="panel box bg-success">
                        <div class="box-header">
                            <h5>
                                <a data-toggle="collapse" href="#message_{{ $message->id }}">
                                    @if($topic->type == 'direct')
                                        {{ $message->sender->name }} {{ $message->sender->surname }}
                                    @else
                                        @if($message->receiver_model == \Spatie\Permission\Models\Role::class)
                                            {{ $message->sender->name }} {{ $message->sender->surname }}
                                        @else
                                            {{ $topic->roleReceiver->role_name }}
                                        @endif
                                    @endif
                                </a>
                                <span class="mailbox-read-time pull-right">@lang('Sent at') {{ $message->sent_at->diffForHumans() }}</span>
                            </h5>
                        </div>
                        <div id="message_{{ $message->id }}"
                             class="panel-collapse collapse {{ !$message->readByUser() || $loop->first ? 'in' : '' }}">
                            <div class="box-body" aria-expanded="{{ !$message->readByUser() ? 'true' : 'false' }}">
                                <div>{!! $message->content !!}</div>
                                @include('partials._attachments', ['item' => $message])
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        {{ html()->form()->close() }}
    </div>

</div>

@php
    $isTopicReadByUser = !!App::make('unreadTopics')->filter(function ($item) use ($topic) {
        return $item->id === $topic->id;
    })->count();
@endphp
@if($isTopicReadByUser)
<make-messages-as-read :topic="{{ json_encode($topic) }}" :route="'{{ route('admin.messenger.read', [$topic]) }}'"></make-messages-as-read>
@endif
@stop

