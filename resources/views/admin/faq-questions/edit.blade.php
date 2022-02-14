@extends('layouts.admin')

@section('title', $faqQuestion::getMsgTrans('update_heading'))

@section('content')
    @include('partials._content-heading', ['title' => $faqQuestion::getMsgTrans('update_heading')])

    @include('partials._alerts')
    {{ html()->modelForm($faqQuestion, 'PUT', route('admin.faq-questions.update', [$faqQuestion]))->acceptsFiles()->open() }}

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                @include('partials.inputs._select', ['name' => 'category_id', 'label' => $faqQuestion::getAttrsTrans('category_id').'*', 'options' => $categories])
            </div>
            <div class="row">
                @include('partials.inputs._textarea-editor', ['name' => 'question_text', 'label' => $faqQuestion::getAttrsTrans('question_text').'*'])
            </div>
            <div class="row">
                @include('partials.inputs._textarea-editor', ['name' => 'answer_text', 'label' => $faqQuestion::getAttrsTrans('answer_text').'*'])
                @include('partials.inputs._file', ['name' => 'attachments', 'label' => __('Attachments'), 'previewContainer' => 'attachments-preview-container', 'multiple' => true])
            </div>
            @include('partials._attachments', ['item' => $faqQuestion, 'canMediaBeenDeleted' => true])

        </div>
    </div>

    <div class="pull-left">
        @include('partials._submit-crud-form-action-btn')
    </div>

    <div class="pull-right">
        <a href="{{ route('admin.faq-questions.index') }}" class="btn btn-primary">@lang('Back')</a>
    </div>
    {{ html()->form()->close() }}

@stop

