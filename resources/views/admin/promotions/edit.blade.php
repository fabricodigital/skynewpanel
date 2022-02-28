@extends('layouts.admin')

@section('title', $promotion::getMsgTrans('update_heading'))

@section('content')
    @include('partials._content-heading', ['title' => $promotion::getMsgTrans('update_heading')])

    @include('partials._alerts')
    {{ html()->modelForm($promotion, 'PUT', route('admin.promotions.update', [$promotion]))->open() }}

    <div class="panel panel-default">
        <div class="panel-body">

            <div class="row">
                @include('partials.inputs._text', ['name' => 'nome', 'label' => $promotion::getAttrsTrans('nome').'*'])
            </div>

        </div>
    </div>

    <div class="pull-left">
        @include('partials._submit-crud-form-action-btn')
    </div>

    <div class="pull-right">
        <a href="{{ route('admin.promotions.index') }}" class="btn btn-primary">@lang('Back')</a>
    </div>
    {{ html()->form()->close() }}

@stop

