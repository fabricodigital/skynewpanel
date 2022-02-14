<li class="{{ $item->isActive() ? 'active menu-open' : '' }}">
    <a href="{{ route($item->getRoute()) }}">
        <i class="fa {{ $item->getIcon() }}"></i>
        <span>{{ $item->getTitle() }}</span>
        {!! $item->getAppendHtml() !!}
    </a>
</li>
