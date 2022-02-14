@if(method_exists($policy, 'dt_view') && $policy->dt_view(Auth::user(), $row, $viewAllPermission, $viewOwnPermission))
    <a href="{{ route($routeKey . '.show', [$row]) }}" class="btn btn-xs btn-info" title="@lang('View')">
        <i class="fa fa-eye"></i>
    </a>
@endif
@if(method_exists($policy, 'dt_update') && $policy->dt_update(Auth::user(), $row, $updateAllPermission, $updateOwnPermission))
    <a href="{{ route($routeKey . '.edit', [$row]) }}" class="btn btn-xs btn-success" title="@lang('Update')">
        <i class="fa fa-pencil"></i>
    </a>
@endif
@if(method_exists($policy, 'dt_restore') && $policy->dt_restore(Auth::user(), $row, $restoreAllPermission, $restoreOwnPermission))
    {{ html()->form('POST', route($routeKey . '.restore', ['id' => $row->id]))->class('')->attributes(['style' => 'display: inline-block;'])->open() }}
    <input type="hidden" name="restore_relations" value="0" class="data-table-restore-relations">
    <button class="btn btn-success btn-xs data-table-restore-single" type="submit"
            data-duplicate_message="@lang('Are you sure you want to restore the selected item?')" title="@lang('Restore')">
        <i class="fa fa-undo"></i>
    </button>
    {{ html()->form()->close() }}
@endif
@if(method_exists($policy, 'dt_delete') && $policy->dt_delete(Auth::user(), $row, $deleteAllPermission, $deleteOwnPermission))
    {{ html()->form('DELETE', route($routeKey . '.destroy', [$row]))->class('')->attributes(['style' => 'display: inline-block;'])->open() }}
    @if($deleteForeverPermission)
        <input type="hidden" name="delete_forever" value="0" class="data-table-delete-single-forever">
    @endif
    <button class="btn btn-xs btn-danger data-table-delete-single"
            type="submit"
            title="@lang('Delete')"
            data-delete_message="@lang('Are you sure you want to delete the selected item?')"
            data-can_delete_forever="{{ $deleteForeverPermission ? 1 : 0 }}"
            data-is_deleted="{{ !empty($row->is_deleted) ? 1 : 0 }}"
    >
        <i class="fa fa-remove"></i>
    </button>
    {{ html()->form()->close() }}
@endif
