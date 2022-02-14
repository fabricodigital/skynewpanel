@extends('layouts.admin')

@section('title', $dashboard::getMsgTrans('create_heading'))

@section('content')
    @include('partials._content-heading', ['title' => $dashboard::getMsgTrans('create_heading')])

    @include('partials._alerts')

    {{ html()->form('POST', route('admin.dashboards.store'))->class('')->acceptsFiles()->open() }}

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                @include('partials.inputs._file', [
                    'name' => 'dashboard_image',
                    'label' => __('Browse...').'*',
                    'previewContainer' =>
                    'img_box-preview-container'
                ])
            </div>
            <div class="row">
                @include('partials.inputs._select', [
                    'name' => 'role_id',
                    'label' => $dashboard::getAttrsTrans('role').'*',
                    'width' => 6,
                    'options' => $roles,
                ])

                @include('partials.inputs._select', [
                    'name' => 'account_id',
                    'label' => $dashboard::getAttrsTrans('account').'*',
                    'width' => 6,
                    'options' => $accounts,
                ])
            </div>

            <div class="row">
                @include('partials.inputs._text', [
                    'name' => 'name',
                    'label' => $dashboard::getAttrsTrans('name').'*',
                    'width' => 6,
                    'attributes' => ['maxlength' => 25]
                ])

                @include('partials.inputs._text', [
                    'name' => 'description',
                    'label' => $dashboard::getAttrsTrans('description').'*',
                    'width' => 6,
                    'attributes' => ['maxlength' => 230]
                ])
            </div>

            <div class="row">
                <div class="col-md-12">
                    {{ html()->label($dashboard::getAttrsTrans('widgets'))->class('form-check-label') }}
                </div>
                @foreach ($widgets as $widget)
                    @include('partials.inputs._checkbox', ['name' => 'widgets[]', 'label' => $widget->name, 'value' => $widget->id])
                @endforeach
            </div>
        </div>
    </div>

    <div class="pull-left">
        @include('partials._submit-crud-form-action-btn')
    </div>

    <div class="pull-right">
        <a href="{{ route('admin.dashboards.index') }}" class="btn btn-primary">@lang('Back')</a>
    </div>
    {{ html()->form()->close() }}
@stop
