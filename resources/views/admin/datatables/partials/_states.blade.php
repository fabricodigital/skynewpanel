@switch($state)
    @case('in_progress')
        <span class="label label-info">@lang('In progress')</span>
    @break

    @case('completed')
        <span class="label label-success">@lang('Completed')</span>
    @break

    @case('failed')
        <span class="label label-danger">@lang('Failed')</span>
    @break
@endswitch