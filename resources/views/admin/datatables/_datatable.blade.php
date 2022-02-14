@php
    $additionalButtons = $additionalButtons ?? [];
    $buttonsBasePath = 'admin.datatables.partials.buttons.';
@endphp
<p class="page-buttons hidden-xs">

    @foreach(array_filter($additionalButtons, function ($aB) {
        return $aB['position'] == 'left';
    }) as $additionalButton)
        @php
            $additionalButton['variables'] = $additionalButton['variables'] ?? [];
        @endphp
        @includeIf($buttonsBasePath . $additionalButton['partial_name'], $additionalButton['variables'])
    @endforeach

    @can('create', $permissionClass)
        <a href="{{ route('admin.' . $routeNamespace . '.create') }}" class="btn btn-success">
            @lang('Add')
        </a>
    @endcan
    @can('export', $permissionClass)
        <button class="btn btn-info btn-export-dt" data-target_table="{{ $dataTableObject['id'] }}">
            @lang('Export')
        </button>
    @endcan
    <button class="btn btn-primary btn-reset-filters-dt" data-target_table="{{ $dataTableObject['id'] }}">
        @lang('Reset filters')
    </button>
    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#{{ $dataTableObject['id'] }}ColumnsVisibilityModal">@lang('Columns')</button>

    @foreach(array_filter($additionalButtons, function ($aB) {
        return $aB['position'] == 'right';
    }) as $additionalButton)
        @php
            $additionalButton['variables'] = $additionalButton['variables'] ?? [];
        @endphp
        @includeIf($buttonsBasePath . $additionalButton['partial_name'], $additionalButton['variables'])
    @endforeach
</p>

<div class="page-buttons box box-default collapsed-box visible-xs">
    <div class="box-header">
        <h3 class="box-title" data-widget="collapse">@lang('Actions')</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
    </div>
    <div class="box-body">
        @foreach(array_filter($additionalButtons, function ($aB) {
            return $aB['position'] == 'left';
        }) as $additionalButton)
            @php
                $additionalButton['variables'] = $additionalButton['variables'] ?? [];
            @endphp
            @includeIf($buttonsBasePath . $additionalButton['partial_name'], $additionalButton['variables'])
        @endforeach
        @can('create', $permissionClass)
            <a href="{{ route('admin.' . $routeNamespace . '.create') }}" class="btn btn-success">
                @lang('Add')
            </a>
        @endcan
        @can('export', $permissionClass)
            <button class="btn btn-info btn-export-dt" data-target_table="{{ $dataTableObject['id'] }}">
                @lang('Export')
            </button>
        @endcan
        <button class="btn btn-primary btn-reset-filters-dt" data-target_table="{{ $dataTableObject['id'] }}">
            @lang('Reset filters')
        </button>
        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#{{ $dataTableObject['id'] }}ColumnsVisibilityModal">@lang('Columns')</button>
            @foreach(array_filter($additionalButtons, function ($aB) {
                return $aB['position'] == 'right';
            }) as $additionalButton)
            @php
                $additionalButton['variables'] = $additionalButton['variables'] ?? [];
            @endphp
            @includeIf($buttonsBasePath . $additionalButton['partial_name'], $additionalButton['variables'])
        @endforeach
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading panel-view">
        <div class="hidden-xs">
            @lang('Table view')
            @include('admin.datatables.partials._views-selection', ['targetTable' => $dataTableObject['id'], 'selectId' => $dataTableObject['id'] . "SelectViewXL"])
        </div>
        <div class="box collapsed-box no-border visible-xs">
            <div class="box-header">
                <div class="box-title" data-widget="collapse">@lang('Table view')</div>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                </div>
            </div>
            <div class="box-body">
                @include('admin.datatables.partials._views-selection', ['targetTable' => $dataTableObject['id'], 'selectId' => $dataTableObject['id'] . "SelectViewXS"])
            </div>
        </div>
    </div>
    <div class="panel-body">
        @include('admin.datatables.partials._template', [ 'dataTableObject' => $dataTableObject ])
    </div>
</div>
