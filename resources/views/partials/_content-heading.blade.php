@inject('menu', 'App\Libraries\Menu\Menu')
<h3 class="page-title">
    <span class="page-title__title">
        <i class="fa {{ $menu->getActiveIcon() }}"></i> {{ $title }}
    </span>
    @if (!empty($deleted))
        <span class="page-title__state">
            <span class="label label-danger">@lang("Deleted")</span>
        </span>
    @endif
</h3>
