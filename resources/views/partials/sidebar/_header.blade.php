<li class="header">{{ $item->getTitle() }}</li>
@foreach($item->getSubMenu() as $subItem)
    @include('partials.sidebar._switch-types', ['item' => $subItem])
@endforeach
