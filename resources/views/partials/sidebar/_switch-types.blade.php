@switch($item->getType())
    @case('header')
        @include('partials.sidebar._header', ['item' => $item])
        @break
    @case('dropdown')
        @include('partials.sidebar._dropdown', ['item' => $item])
        @break
    @case('link')
        @include('partials.sidebar._link', ['item' => $item])
        @break
    @case('partial')
        @include($item->getPartialFile(), ['item' => $item])
        @break
@endswitch
