@extends('layouts.admin')

@section('title', $note::getMsgTrans('update_heading'))

@section('content')
    @include('partials._content-heading', ['title' => $note::getMsgTrans('update_heading')])

    @include('partials._alerts')
    {{ html()->modelForm($note, 'PUT', route('admin.notes.update', [$note]))->open() }}

    <div class="panel panel-default">
        <div class="panel-body">

            <div class="row">
                @include('partials.inputs._text', ['name' => 'notes', 'label' => $note::getAttrsTrans('notes').'*'])
            </div>

        </div>
    </div>

    <div class="pull-left">
        @include('partials._submit-crud-form-action-btn')
    </div>

    <div class="pull-right">
        <a href="{{ route('admin.notes.index') }}" class="btn btn-primary">@lang('Back')</a>
    </div>
    {{ html()->form()->close() }}

@stop

