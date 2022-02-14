@switch($label)
    @case('locale')
        @switch($value[0])
            @case('en')
                <li>
                    <b>@lang('locale-form-label'):</b> <i class="flag-icon flag-icon-gb"></i><span class="title">@lang('English')</span>
                </li>
            @break

            @case('it')
                <li>
                    <b>@lang('locale-form-label'):</b> <i class="flag-icon flag-icon-gb"></i><span class="title">@lang('Italian')</span>
                </li>
            @break

            @case('bg')
                <li>
                    <b>@lang('locale-form-label'):</b> <i class="flag-icon flag-icon-gb"></i><span class="title">@lang('Bulgarian')</span>
                </li>
            @break
        @endswitch
    @break
    @case('state')
        @if($model == "\App\Models\Admin\User")
            <li>
                <b>{{ \App\Models\Admin\User::getAttrsTrans('state') }}:</b> @include('admin.users.partials._states', ['state' => $value[0]])
            </li>
        @else
            @if(method_exists($model, 'getAttrsTrans'))
                <li>
                    <b>{{ $model::getAttrsTrans($label) }}:</b> <span class="label label-info">{{ $model::getEnumsTrans($label, $value[0]) }}</span>
                </li>
            @else
                <li>
                    <b>{{ $label }}:</b> <span class="label label-info">{{ $value[0] }}</span>
                </li>
            @endif
        @endif
    @break
    @default
        @if(method_exists($model, 'getAttrsTrans'))
            <li>
                <b>{{ $model::getAttrsTrans($label) }}:</b> <span class="label label-info">{{ $model::getEnumsTrans($label, $value[0]) }}</span>
            </li>
        @else
        <li>
            <b>{{ $label }}:</b> <span class="label label-info">{{ $value[0] }}</span>
        </li>
        @endif
    @break
@endswitch
