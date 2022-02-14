<li>
    @if($value[0])
        <b>{{ $model::getAttrsTrans($key) ?? __($key) }}:</b> <span class="label label-success">@lang('Yes')</span>
    @else
        <b>{{ $model::getAttrsTrans($key) ?? __($key) }}:</b> <span class="label bg-red">@lang('No')</span>
    @endif
</li>