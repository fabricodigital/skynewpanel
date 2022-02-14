@if(Auth::user()->can('dt_update', [$row, $updateAllPermission, $updateOwnPermission]))
    <a href="{{ route($routeKey.'.edit', $row) }}">{{ $text }}</a>
@elseif(Auth::user()->can('dt_view', [$row, $viewAllPermission, $viewOwnPermission]))
    <a href="{{ route($routeKey.'.show', $row) }}">{{ $text }}</a>
@else
    {{ $text }}
@endif