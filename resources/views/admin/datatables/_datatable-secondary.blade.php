<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ $title }}</h3>
        @can('export', $permissionClass)
            <button class="btn btn-sm btn-info btn-export-dt" data-target_table="{{ $dataTableObject['id'] }}">
                @lang('Export')
            </button>
        @endcan
        @if(empty($disableResetFilters))
        <button class="btn btn-sm btn-primary btn-reset-filters-dt" data-target_table="{{ $dataTableObject['id'] }}">
            @lang('Reset filters')
        </button>
        @endif
        @if(empty($disableColumnsSelect))
        <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#{{ $dataTableObject['id'] }}ColumnsVisibilityModal">@lang('Columns')</button>
        @endif        
        @if(isset($routeAddNew))
            @can('create', $permissionClass)
                <div class="pull-right">
                    <a href="{{ $routeAddNew }}"
                       class="btn btn-sm btn-success">
                        @lang('Add')
                    </a>
                </div>
            @endcan
        @endif
    </div>

    <div class="panel-body">
        @include('admin.datatables.partials._template', [ 'dataTableObject' => $dataTableObject ])
    </div>
</div>
