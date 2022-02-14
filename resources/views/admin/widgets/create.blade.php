@extends('layouts.admin')

@section('title', $widget::getMsgTrans('create_heading'))

@section('content')
    @include('partials._content-heading', ['title' => $widget::getMsgTrans('create_heading')])

    @include('partials._alerts')

    {{ html()->form('POST', route('admin.widgets.store'))->class('')->open() }}

    <div class="panel panel-default">
        <div class="panel-body">
            
            <div class="row">
                @include('partials.inputs._text', ['name' => 'name', 'label' => $widget::getAttrsTrans('name').'*', 'width' => 4])

                @include('partials.inputs._select', [
                    'name' => 'type',
                    'label' => $widget::getAttrsTrans('type').'*',
                    'width' => 4,
                    'options' => $widget::getEnumsTrans('type'),
                ])

                @include('partials.inputs._select', [
                    'name' => 'width',
                    'label' => $widget::getAttrsTrans('width').'*',
                    'width' => 4,
                    'options' => $widget::getEnumsTrans('width'),
                ])
            </div>

            <div class="row">
                @include('partials.inputs._textarea', ['name' => 'description', 'label' => $widget::getAttrsTrans('description')])
            </div>

            <div class="row">
                @include('partials.inputs._textarea', ['name' => 'query', 'label' => $widget::getAttrsTrans('query').'*'])
            </div>
        </div>
    </div>

    <div class="pull-left">
        @include('partials._submit-crud-form-action-btn')
    </div>

    <div class="pull-right">
        <a href="{{ route('admin.widgets.index') }}" class="btn btn-primary">@lang('Back')</a>
    </div>
    {{ html()->form()->close() }}
@stop

