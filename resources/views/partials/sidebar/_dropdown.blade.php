<li class="treeview {{ $item->isActive() ? 'active menu-open' : '' }}">
    <a href="#">
        <i class="fa {{ $item->getIcon() }}"></i>
        <span>{{ $item->getTitle() }}</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        @foreach($item->getSubMenu() as $subItem)
            @include('partials.sidebar._switch-types', ['item' => $subItem])
        @endforeach
    </ul>
</li>
