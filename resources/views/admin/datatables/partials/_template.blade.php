<div class="alert alert-warning {{ $dataTableObject['id'] }}FiltersAlert" style="display: none">
    <i class="fa fa-filter"></i>
    @lang('Filters applied. Reset filters to initialize current table view.')
</div>
<table class="ajaxDataTable table table-bordered table-striped table-responsive nowrap"
    data-params="{{ json_encode($dataTableObject) }}"
    data-page-length="{{ $dataTableObject['pageLength'] }}"
    @isset($dataTableObject['createdRowCallback']) data-created_row_callback="{{ $dataTableObject['createdRowCallback'] }}" @endisset
    id="{{ $dataTableObject['id'] }}">
    <thead>
        <tr class="datatable__filters">
            @foreach($dataTableObject['columns'] as $column)
                <th>
                    @isset($column["filter"])
                        @include('admin.datatables.filters._'.$column["filter"]["type"], [
                            'name' => $column["data"],
                            'tableTarget' => $dataTableObject["id"],
                            'options' => isset($column["filter"]["options"]) ? $column["filter"]["options"] : [],
                            'selected' => isset($column["filter"]["selected"]) ? $column["filter"]["selected"] : [],
                            'columnTarget' => ".dt_col_".$column["data"],
                            'url' => isset($column['filter']['url']) ? $column['filter']['url'] : null,
                        ])
                    @else
                        <input type="text" disabled="true" class="datatable__filter datatable__filter--empty form-control">
                    @endisset
                </th>
            @endforeach
        </tr>
        <tr>
            @foreach($dataTableObject['columns'] as $column)
                <th>{{ $column['label'] }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody></tbody>
</table>

@include('admin.datatables.partials._columns-visibility-modal', [
    'dataTableColumns' => $dataTableObject['columns'],
    'modalId' => $dataTableObject['id'].'ColumnsVisibilityModal',
    'targetTable' => $dataTableObject['id']
])
