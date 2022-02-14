@extends('layouts.admin')

@section('title', $CRUD_lcfirst::getMsgTrans('create_heading'))

@section('content')
    @include('partials._content-heading', ['title' => $CRUD_lcfirst::getMsgTrans('create_heading')])

    @include('partials._alerts')

    {{ html()->form('POST', route('admin.CRUD_route.store'))->class('')->open() }}

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                @include('partials.inputs._text', ['name' => 'CRUD_column_name', 'label' => $CRUD_lcfirst::getAttrsTrans('CRUD_column_name').'*'])
            </div>
        </div>
    </div>

    <div class="pull-left">
        @include('partials._submit-crud-form-action-btn')
    </div>

    <div class="pull-right">
        <a href="{{ route('admin.CRUD_route.index') }}" class="btn btn-primary">@lang('Back')</a>
    </div>
    {{ html()->form()->close() }}
@stop

