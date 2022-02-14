@can('restore', $model)
    @if (!empty($model->deleted_at))
        {{ html()->form('POST', route('admin.' . $routeNamespace . '.restore', ['id' => $model->id]))->class('')->attributes(['style' => 'display: inline-block;'])->open() }}
        <input type="hidden" name="restore_relations" value="0" class="data-table-restore-relations">
        <button class="btn btn-success data-table-restore-single" type="submit"
                data-duplicate_message="@lang('Are you sure you want to restore the selected item?')" title="@lang('Restore')">
            @lang('Restore')
        </button>
        {{ html()->form()->close() }}
    @endif
@endcan
