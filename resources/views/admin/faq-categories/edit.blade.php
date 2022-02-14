@extends('layouts.admin')

@section('title', $faqCategory::getMsgTrans('update_heading'))

@section('content')
    @include('partials._content-heading', ['title' => $faqCategory::getMsgTrans('update_heading')])

    @include('partials._alerts')
    {{ html()->modelForm($faqCategory, 'PUT', route('admin.faq-categories.update', [$faqCategory]))->open() }}

    <div class="panel panel-default">
        <div class="panel-body">

            <div class="row">
                @include('partials.inputs._text', ['name' => 'title', 'label' => $faqCategory::getAttrsTrans('title').'*'])
            </div>

        </div>
    </div>

    <div class="pull-left">
        @include('partials._submit-crud-form-action-btn')
    </div>

    <div class="pull-right">
        <a href="{{ route('admin.faq-categories.index') }}" class="btn btn-primary">@lang('Back')</a>
    </div>
    {{ html()->form()->close() }}

@stop

