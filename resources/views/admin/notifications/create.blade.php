@extends('layouts.admin')

@section('title', $notification::getMsgTrans('create_heading'))

@section('content')
    @include('partials._content-heading', ['title' => $notification::getMsgTrans('create_heading')])

    @include('partials._alerts')

    {{ html()->form('POST', route('admin.notifications.store'))->class('')->acceptsFiles()->open() }}

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                @include('partials.inputs._text', ['name' => 'title', 'label' => $notification::getAttrsTrans('title').'*', 'width' => 6])

                @include('partials.inputs._date-time-range-picker', [
                    'name' => 'duration',
                    'startName' => 'start',
                    'startValue' => \Carbon\Carbon::now()->startOfDay()->format('d/m/Y H:i'),
                    'endName' => 'end',
                    'endValue' => \Carbon\Carbon::now()->endOfDay()->format('d/m/Y H:i'),
                    'label' => __('duration-form-label').'*',
                    'width' => 6
                ])
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    <legend>@lang('Visibility')</legend>
                </div>

                @include('partials.inputs._select-multi', [
                    'name' => 'roles',
                    'label' => $notification::getAttrsTrans('roles').'*',
                    'width' => 6,
                    'options' => $roles,
                    'multiple' => true
                ])

                <div class="col-xs-12 form-group">
                    <hr/>
                </div>
            </div>

            <div class="row">
                @include('partials.inputs._textarea-editor', ['name' => 'text', 'label' => $notification::getAttrsTrans('text').'*'])
            </div>
            <div class="row">
                @include('partials.inputs._file', ['name' => 'attachments', 'label' => __('Attachments'), 'previewContainer' => 'attachments-preview-container', 'multiple' => true])
            </div>
        </div>
    </div>

    <div class="pull-left">
        @include('partials._submit-crud-form-action-btn')
    </div>

    <div class="pull-right">
        <a href="{{ route('admin.notifications.index') }}" class="btn btn-primary">@lang('Back')</a>
    </div>
    {{ html()->form()->close() }}
@stop

