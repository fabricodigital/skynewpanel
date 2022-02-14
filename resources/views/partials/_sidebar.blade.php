<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            @php
            $profileImage = Auth::user()->getMedia('profile-image')
            @endphp
            <div class="pull-left image">
                {{ html()->img(count($profileImage) ? $profileImage[0]->getUrl() : asset('images/admin-panel/profile-placeholder.png'))->class('profile-img img-responsive img-circle') }}
            </div>
            <div class="pull-left info">
                <p>
					<i class="fa fa-circle online-offline
                        @if(Auth::user()->activity_status == 'offline')
                            text-red
                        @elseif(Auth::user()->activity_status == 'inactive')
                            text-orange
                        @else
                            text-green
                        @endif
                    "></i>
					<a href="{{ route('admin.profile.edit') }}">{{ Auth::user()->name }}</a>
				</p>
                <span class="role">{{ implode(', ', app()->make('loggedUserRolesNames')) }}</span></a>
            </div>
        </div>

        <ul class="sidebar-menu" data-widget="tree">
            @inject('menu', 'App\Libraries\Menu\Menu')
            @foreach($menu->get() as $item)
                @include('partials.sidebar._switch-types', ['item' => $item])
            @endforeach
        </ul>

    </section>
</aside>
