@switch($type)
    @case('created')
        <span class="label bg-green">@lang('Created')</span>
        @break
    @case('updated')
        <span class="label bg-yellow">@lang('Updated')</span>
        @break
    @case('deleted')
        <span class="label bg-red">@lang('Deleted')</span>
        @break
    @case('deleted forever')
        <span class="label bg-red">@lang('Deleted forever')</span>
        @break
    @case('restored')
        <span class="label bg-green">@lang('Restored')</span>
        @break
@endswitch
