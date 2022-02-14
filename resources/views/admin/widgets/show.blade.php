@extends('layouts.admin')

@section('title', $widget::getMsgTrans('view_heading'))

@section('content')
    @include('partials._content-heading', ['title' => $widget::getMsgTrans('view_heading'), 'deleted' => !empty($widget->deleted_at) ? true : false])

    <div class="panel panel-default{{ !empty($widget->deleted_at) ? ' record-deleted' : '' }}">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>{{ $widget::getAttrsTrans('name') }}</th>
                            <td field-key='name'>{{ $widget->name }}</td>
                        </tr>
                        <tr>
                            <th>{{ $widget::getAttrsTrans('type') }}</th>
                            <td field-key='type'>{{ $widget->type }}</td>
                        </tr>
                        <tr>
                            <th>{{ $widget::getAttrsTrans('width') }}</th>
                            <td field-key='width'>{{ $widget->width }}</td>
                        </tr>
                        <tr>
                            <th>{{ $widget::getAttrsTrans('description') }}</th>
                            <td field-key='description'>{{ $widget->description }}</td>
                        </tr>
                        <tr>
                            <th>{{ $widget::getAttrsTrans('query') }}</th>
                            <td field-key='query'>{{ $widget->query }}</td>
                        </tr>
                        <tr>
                            <th>{{ $widget::getAttrsTrans('accounts') }}</th>
                            <td field-key='accounts'>{!! $accounts !!}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="pull-left">
        @include('partials._restore-action-btn', ['model' => $widget, 'routeNamespace' => 'widgets'])
    </div>

    <div class="pull-right">
        <a href="{{ route('admin.widgets.index') }}" class="btn btn-primary">@lang('Back')</a>
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

