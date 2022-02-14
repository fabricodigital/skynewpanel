@switch($locale)
    @case(null)
    @break

    @case('en')
    <i class="flag-icon flag-icon-gb"></i>
    @break

    @default
    <i class="flag-icon flag-icon-{{ $locale }}"></i>
    @break
@endswitch
