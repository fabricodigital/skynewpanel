<div class="datatable__view">
    <div class="col-sm-4 col-xs-12">
        {{ html()->form('POST', route('admin.datatables.post.change_user_view'))->open() }}
        <select name="view"
                class="select2-create_tag form-control"
                data-placeholder="@lang('Select...')"
                data-target_table="{{ $dataTableObject['id'] }}"
                id="{{ $selectId }}"
        >
            @foreach($dataTableObject['views'] as $view)
                <option value="{{ $view }}" @if($dataTableObject['lastUsedView'] == $view) selected="selected" @endif>{{ $view }}</option>
            @endforeach
        </select>
        {{ html()->hidden('target_table', $dataTableObject['id']) }}
        {{ html()->hidden('table_params', null) }}
        {{ html()->form()->close() }}
    </div>
    <div class="col-sm-1 col-xs-6">
        {{ html()->form('POST', route('admin.datatables.post.save_user_view'))->open() }}
        {{ html()->hidden('target_table', $dataTableObject['id']) }}
        {{ html()->hidden('table_params', null) }}
        {{ html()->hidden('view_name', null) }}
        <button class="btn btn-success btn-block dt_save-view_btn" data-target_select="{{ $selectId }}" data-table_target="{{ $dataTableObject['id'] }}" title="@lang('Save')"><i class="fa fa-check"></i></button>
        {{ html()->form()->close() }}
    </div>
    <div class="col-sm-1 col-xs-6">
        {{ html()->form('DELETE', route('admin.datatables.post.delete_user_view'))->open() }}
        {{ html()->hidden('target_table', $dataTableObject['id']) }}
        {{ html()->hidden('view_name', null) }}
        <button class="btn btn-danger btn-block dt_delete-view_btn" data-target_select="{{ $selectId }}" data-table_target="{{ $dataTableObject['id'] }}" title="@lang('Delete')">
            <i class="fa fa-close"></i>
        </button>
        {{ html()->form()->close() }}
    </div>

    <div class="col-sm-6 col-xs-6 datatable__view-info-message" data-target_table="{{ $dataTableObject['id'] }}" style="display: none;">
        @lang('Save to apply changes...')
    </div>
</div>

@section('javascript')
    @parent

@stop
