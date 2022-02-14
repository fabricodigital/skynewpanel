@extends('layouts.admin')

@section('title', $account::getMsgTrans('create_heading'))

@section('content')
    @include('partials._content-heading', ['title' => $account::getMsgTrans('create_heading')])

    @include('partials._alerts')

    {{ html()->form('POST', route('admin.accounts.store'))->class('')->acceptsFiles()->open() }}

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4 form-group" style="min-height: 150px;">
                    @include('partials.inputs._file-upload', ['name' => 'logo', 'label' => __('Logo'), 'previewContainer' => 'avatar-preview-container', 'multiple' => false])
                </div>
            </div>
            <div class="row">
                @include('partials.inputs._text', ['name' => 'name', 'label' => $account::getAttrsTrans('name').'*'])
            </div>
            <div class="row">
                @include('partials.inputs._text', ['name' => 'marketplace_id', 'label' => __('Marketplace ID').'*'])
            </div>
        </div>
    </div>

    <div class="pull-left">
        @include('partials._submit-crud-form-action-btn')
    </div>

    <div class="pull-right">
        <a href="{{ route('admin.accounts.index') }}" class="btn btn-primary">@lang('Back')</a>
    </div>
    {{ html()->form()->close() }}
@stop
