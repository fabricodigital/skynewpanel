@extends('admin.messenger.template')

@section('title', __('New message'))

@section('messenger-content')

@php
    $activeTab = old('type') ? old('type') : 'direct';
@endphp

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        @can('create_direct messages', \App\Models\Admin\MessengerTopic::class)
        <li class="tab-light-blue {{ $activeTab == 'direct' ? 'active' : '' }}">
            <a href="#direct" data-toggle="tab">
                <i class="fa fa-user text-light-blue"></i>
                @lang('Direct')
            </a>
        </li>
        @endcan

        @can('create_help messages', \App\Models\Admin\MessengerTopic::class)
        <li class="tab-red tab-danger {{ $activeTab == 'help' || !Auth::user()->can('create_direct messages', \App\Models\Admin\MessengerTopic::class) ? 'active' : '' }}">
            <a href="#help" data-toggle="tab">
                <i class="fa fa-question text-red"></i>
                @lang('Help')
            </a>
        </li>
        @endcan
    </ul>
    <div class="tab-content">
        @can('create_direct messages', \App\Models\Admin\MessengerTopic::class)
        <div class="tab-pane {{ $activeTab == 'direct' ? 'active' : '' }}" id="direct">
            {{ html()->form('POST', route('admin.messenger.store'))->class('validate')->novalidate()->acceptsFiles()->open() }}

            <div class="row">
                <div class="col-md-12 form-group">
                    <select name="receiver" id="receiver"
                            class="form-control select2-ajax"
                            data-placeholder="{{ \App\Models\Admin\MessengerMessage::getAttrsTrans('receiver') }}:"
                            data-url="{{ route('admin.ajax.users') }}"
                            data-text_field="text">
                        @if(old('receiver'))
                            <option value="{{ old('receiver') }}" selected="selected">{{ old('receiver-label') }}</option>
                        @endif
                    </select>
                    <input type="hidden" name="receiver-label"/>
                </div>

                <div class="col-md-12 form-group">
                    <input type="text" name="subject" value="{{ $activeTab == 'direct' ? old('subject') : '' }}" placeholder="{{ \App\Models\Admin\MessengerMessage::getAttrsTrans('subject') }}:" class="form-control">
                </div>

                <div class="col-md-12 form-group">
                    <textarea name="content" id="direct_content" cols="50" rows="10" placeholder="{{ \App\Models\Admin\MessengerMessage::getAttrsTrans('content') }}:" class="form-control editor">{{ $activeTab == 'direct' ? old('content') : '' }}</textarea>
                </div>

                <div class="col-md-12 form-group">
                    <ul class="list-group preview-container direct-attachments-preview-container">

                    </ul>
                    @include('partials.inputs._file-upload', ['name' => 'attachments[]', 'label' => __('Attachments'), 'previewContainer' => 'direct-attachments-preview-container', 'multiple' => true])
                </div>

                <div class="col-md-12 form-group">
                    <input type="hidden" name="type" value="direct">
                    <input type="hidden" name="receiver_model" value="{{ get_class( new \App\Models\Admin\User()) }}">
                    <a href="{{ route('admin.messenger.index') }}" class="btn btn-default">
                        <i class="fa fa-times"></i> @lang('Discard')
                    </a>
                    <div class="pull-right">
                        {{ html()->button('<i class="fa fa-envelope-o"></i> '.__('Send'))->type('submit')->class('btn btn-primary') }}
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            {{ html()->form()->close() }}
        </div>
        @endcan

        @can('create_help messages', \App\Models\Admin\MessengerTopic::class)
        <div class="tab-pane {{ $activeTab == 'help' || !Auth::user()->can('create_direct messages', \App\Models\Admin\MessengerTopic::class) ? 'active' : '' }}" id="help">
            {{ html()->form('POST', route('admin.messenger.store'))->class('validate')->novalidate()->acceptsFiles()->open() }}
            <div class="row">
                <div class="col-md-12 form-group">
                    <select name="receiver" id="receiver_role"
                            class="form-control select2-rendered"
                            data-placeholder="{{ \App\Models\Admin\MessengerMessage::getAttrsTrans('receiver') }}:">
                        @foreach($roles as $role)
                            <option value=""></option>
                            <option value="{{ $role->id }}" @if(old('receiver') && $role->id == old('receiver')) selected="selected" @endif>{{ $role->role_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-12 form-group">
                    <input type="text" name="subject" value="{{ $activeTab == 'help' ? old('subject') : '' }}" placeholder="{{ \App\Models\Admin\MessengerMessage::getAttrsTrans('subject') }}:" class="form-control">
                </div>

                <div class="col-md-12 form-group">
                    <textarea name="content" id="help_content" cols="50" rows="10" placeholder="{{ \App\Models\Admin\MessengerMessage::getAttrsTrans('content') }}:" class="form-control editor">{{ $activeTab == 'help' ? old('content') : '' }}</textarea>
                </div>

                <div class="col-md-12 form-group">
                    <ul class="list-group preview-container help-attachments-preview-container">

                    </ul>
                    @include('partials.inputs._file-upload', ['name' => 'attachments[]', 'label' => __('Attachments'), 'previewContainer' => 'help-attachments-preview-container', 'multiple' => true])
                </div>

                <div class="col-md-12 form-group">
                    <input type="hidden" name="type" value="help">
                    <input type="hidden" name="receiver_model" value="{{ \App\Models\Admin\Role::class }}">
                    <a href="{{ route('admin.messenger.index') }}" class="btn btn-default">
                        <i class="fa fa-times"></i> @lang('Discard')
                    </a>
                    <div class="pull-right">
                        {{ html()->button('<i class="fa fa-envelope-o"></i> '.__('Send'))->type('submit')->class('btn btn-danger') }}
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            {{ html()->form()->close() }}
        </div>
        @endcan
    </div>
</div>
@stop
