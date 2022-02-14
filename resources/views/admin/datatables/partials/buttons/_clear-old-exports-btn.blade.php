@can('clear_old', \App\Models\Admin\Export::class)
    @php
        $confirmMessage = __('Are you sure you want to delete all exports created before :date', [
            'date' => \Carbon\Carbon::now()
                ->subDays(\App\Models\Admin\Export::EXPIRED_AFTER_DAYS)
                ->format('d/m/Y'),
        ]);
    @endphp
    <button class="btn btn-info clear_old_exports-btn"
        data-url="{{ route('admin.exports.clear_old') }}"
        data-confirm_message="{{ $confirmMessage }}"
        @if($clearOldJob->last_state == 'running' || $clearOldJob->last_state == 'queue') disabled="disabled" @endif

    >
        @if($clearOldJob->last_state == 'running' || $clearOldJob->last_state == 'queue')
            <i class="icon fa fa-spin fa-refresh"></i>&nbsp;
        @endif
        @lang('Clear old exports')
    </button>
@endcan
