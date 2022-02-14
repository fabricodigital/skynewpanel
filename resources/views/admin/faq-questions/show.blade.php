@extends('layouts.admin')

@section('title', $faqQuestion::getMsgTrans('view_heading'))

@section('content')
    @include('partials._content-heading', ['title' => $faqQuestion::getMsgTrans('view_heading'), 'deleted' => !empty($faqQuestion->deleted_at) ? true : false])

    <div class="panel panel-default{{ !empty($faqQuestion->deleted_at) ? ' record-deleted' : '' }}">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>{{ $faqQuestion::getAttrsTrans('category_id') }}</th>
                            <td field-key='category'>{{ $faqQuestion->category->title ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>{{ $faqQuestion::getAttrsTrans('question_text') }}</th>
                            <td field-key='question_text'>{!! $faqQuestion->question_text !!}</td>
                        </tr>
                        <tr>
                            <th>{{ $faqQuestion::getAttrsTrans('answer_text') }}</th>
                            <td field-key='answer_text'>{!! $faqQuestion->answer_text !!}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    @include('partials._attachments', ['item' => $faqQuestion])
                </div>
            </div>

        </div>

    </div>

    <div class="pull-left">
        @include('partials._restore-action-btn', ['model' => $faqQuestion, 'routeNamespace' => 'faq-questions'])
    </div>

    <div class="pull-right">
        <a href="{{ route('admin.faq-questions.index') }}" class="btn btn-primary">@lang('Back')</a>
    </div>

    <div class="clearfix"></div>

    @can('view_all', \App\Models\Admin\Revision::class)
        @include('admin.datatables._datatable-secondary', [
            'dataTableObject' => $revisionsDataTableObject,
            'permissionClass' => \App\Models\Admin\Revision::class,
            'title' => \App\Models\Admin\Revision::getTitleTrans(),
            'disableColumnsSelect' => true
        ])
    @endcan
@stop

