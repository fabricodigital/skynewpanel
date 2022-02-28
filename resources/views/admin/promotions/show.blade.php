@extends('layouts.admin')

@section('title', $promotion::getMsgTrans('view_heading'))

@section('content')
    @include('partials._content-heading', ['title' => $promotion::getMsgTrans('view_heading'), 'deleted' => !empty($promotion->deleted_at) ? true : false])

    <div class="panel panel-default{{ !empty($promotion->deleted_at) ? ' record-deleted' : '' }}">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>{{ $promotion::getAttrsTrans('nome') }}</th>
                            <td field-key='nome'>{{ $promotion->nome }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="pull-left">
        @include('partials._restore-action-btn', ['model' => $promotion, 'routeNamespace' => 'promotions'])
    </div>

    <div class="pull-right">
        <a href="{{ route('admin.promotions.index') }}" class="btn btn-primary">@lang('Back')</a>
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

