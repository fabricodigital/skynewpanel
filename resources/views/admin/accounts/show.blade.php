@extends('layouts.admin')

@section('title', $account::getMsgTrans('view_heading'))

@section('content')
    @include('partials._content-heading', ['title' => $account::getMsgTrans('view_heading'), 'deleted' => !empty($account->deleted_at) ? true : false])

    <div class="panel panel-default{{ !empty($account->deleted_at) ? ' record-deleted' : '' }}">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>{{ $account::getAttrsTrans('name') }}</th>
                            <td field-key='name'>{{ $account->name }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Marketplace ID') }}</th>
                            <td field-key='marketplace_id'>{{ $account->marketplace_id }}</td>
                        </tr>
                        <tr>
                            <th>{{ $account::getAttrsTrans('widgets') }}</th>
                            <td field-key='widgets'>{!! $widgets !!}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="pull-left">
        @include('partials._restore-action-btn', ['model' => $account, 'routeNamespace' => 'accounts'])
    </div>

    <div class="pull-right">
        <a href="{{ route('admin.accounts.index') }}" class="btn btn-primary">@lang('Back')</a>
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

