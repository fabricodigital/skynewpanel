@extends('layouts.admin')

@section('title', $dashboard::getMsgTrans('view_heading'))

@section('content')
    @include('partials._content-heading', ['title' => $dashboard::getMsgTrans('view_heading'), 'deleted' => !empty($dashboard->deleted_at) ? true : false])

    <div class="panel panel-default{{ !empty($dashboard->deleted_at) ? ' record-deleted' : '' }}">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>{{ $dashboard::getAttrsTrans('role') }}</th>
                            <td field-key='name'>{{ $dashboard->role_id }}</td>
                        </tr>
                        <tr>
                            <th>{{ $dashboard::getAttrsTrans('account') }}</th>
                            <td field-key='name'>@if (is_null($dashboard->account_id)) Tutti @else {{ $dashboard->account_id }} @endif</td>
                        </tr>
                        <tr>
                            <th>{{ $dashboard::getAttrsTrans('name') }}</th>
                            <td field-key='name'>{{ $dashboard->name }}</td>
                        </tr>
                        <tr>
                            <th>{{ $dashboard::getAttrsTrans('widgets') }}</th>
                            <td field-key='widgets'>{!! $widgets !!}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="pull-left">
        @include('partials._restore-action-btn', ['model' => $dashboard, 'routeNamespace' => 'dashboards'])
    </div>

    <div class="pull-right">
        <a href="{{ route('admin.dashboards.index') }}" class="btn btn-primary">@lang('Back')</a>
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

