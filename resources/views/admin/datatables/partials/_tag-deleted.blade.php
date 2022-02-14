@if($bool)
    <span data-is_deleted="1" class="label label-success">@lang('Yes')</span>
@else
    <span data-is_deleted="0" class="label label-danger">@lang('No')</span>
@endif
