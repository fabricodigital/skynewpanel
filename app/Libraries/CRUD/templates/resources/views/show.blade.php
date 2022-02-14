@extends('layouts.admin')

@section('title', $CRUD_lcfirst::getMsgTrans('view_heading'))

@section('content')
    @include('partials._content-heading', ['title' => $CRUD_lcfirst::getMsgTrans('view_heading'), 'deleted' => !empty($CRUD_lcfirst->deleted_at) ? true : false])

    <div class="panel panel-default{{ !empty($CRUD_lcfirst->deleted_at) ? ' record-deleted' : '' }}">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>{{ $CRUD_lcfirst::getAttrsTrans('CRUD_column_name') }}</th>
                            <td field-key='CRUD_column_name'>{{ $CRUD_lcfirst->CRUD_column_name }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="pull-left">
        @include('partials._restore-action-btn', ['model' => $CRUD_lcfirst, 'routeNamespace' => 'CRUD_route'])
    </div>

    <div class="pull-right">
        <a href="{{ route('admin.CRUD_route.index') }}" class="btn btn-primary">@lang('Back')</a>
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

