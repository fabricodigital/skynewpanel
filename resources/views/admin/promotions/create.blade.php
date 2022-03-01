@extends('layouts.admin')

@section('title', $promotion::getMsgTrans('create_heading'))

@section('content')
    @include('partials._content-heading', ['title' => $promotion::getMsgTrans('create_heading')])

    @include('partials._alerts')

    {{ html()->form('POST', route('admin.promotions.store'))->class('')->open() }}

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                @include('partials.inputs._text', ['name' => 'nome', 'label' => $promotion::getAttrsTrans('nome').'*'])
            </div>
            <div class="row">
                @include('partials.inputs._text', ['name' => 'abbr', 'label' => __('abbr').'*'])
            </div>
            <div class="row">
                @include('partials.inputs._date-time-picker', [
                    'name' => 'datainizio',
                    'value' => \Carbon\Carbon::now()->startOfDay()->format('d/m/Y H:i'),
                    'label' => __('Data inizio').'*',
                    'width' => 6
                ])
            </div>
            <div class="row">
                @include('partials.inputs._date-time-picker', [
                    'name' => 'datafine',
                    'value' => \Carbon\Carbon::now()->endOfDay()->format('d/m/Y H:i'),
                    'label' => __('Data fine').'*',
                    'width' => 6
                ])
            </div>
            <div class="row">
                @include('partials.inputs._select', [
                    'name' => 'tipologiaskyservice',
                    'label' => __('Tipologiaskyservice').'*',
                    'width' => 4,
                    'options' => $promotion::getEnumsTrans('tipologiaskyservice'),
                ])
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

