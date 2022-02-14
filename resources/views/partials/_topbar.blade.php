<header class="main-header">
    <!-- Logo -->
    <a href="{{ route('admin.home') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">
            {{ html()->img(asset('images/admin-panel/logo-mini.png')) }}
        </span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">
            {{ html()->img(asset('images/admin-panel/logo.png')) }}
        </span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->
                @can('view_index', \App\Models\Admin\MessengerTopic::class)
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-warning" data-toggle="dropdown">
                        <i class="fa fa-envelope"></i>
                        <topbar-unread-messages-counter :unread-messages="{{ json_encode(App::make('unreadMessagesCount')) }}"></topbar-unread-messages-counter>
                    </a>
                    <topbar-unread-topics-dropdown
                            :auth-id="{{ Auth::id() }}"
                            :base-route="'{{ route('admin.messenger.index') }}'"
                            :get-route="'{{ route('admin.ajax.messenger.unread-topics') }}'"
                            :view-all-route="'{{ route('admin.messenger.index') }}'"></topbar-unread-topics-dropdown>

                </li>
                @endcan

                <!-- Notifications: style can be found in dropdown.less -->
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell"></i>
                        <topbar-unread-notifications-counter :unread-notifications-count="{{ App::make('unreadNotificationsCount') }}"></topbar-unread-notifications-counter>
                    </a>
                    <topbar-unread-notifications-dropdown
                            :base-route="'{{ route('admin.notifications.index') }}'"
                            :get-route="'{{ route('admin.ajax.notifications.unread-notifications') }}'"
                            :view-all-route="'{{ route('admin.notifications.index') }}'"></topbar-unread-notifications-dropdown>
                </li>

                <li class="dropdown messages-menu">
                    <a href="#" data-toggle="dropdown" class="dropdown-warning" aria-expanded="false">
                        <i class="flag-icon flag-icon-{{ app()->getLocale() != 'en' ? app()->getLocale() : 'gb' }}"></i>
                    </a>
                    <ul class="dropdown-menu" style="min-width: auto !important;">

                        <li>
                            <ul class="menu">
                                @foreach(config('main.available_languages') as $abbr => $label)

                                    <li class="{{ Auth::user()->locale == $abbr ? 'active' : '' }}" style="width: auto;">
                                        <a href="{{ route('admin.profile.locale', ['locale' => $abbr]) }}" class="{{ Auth::user()->locale == $abbr ? 'active' : '' }}">
                                            <i class="flag-icon flag-icon-{{ $abbr != 'en' ? $abbr : 'gb' }}"></i>
                                            <span class="title">{{ __($label) }}</span>
                                        </a>
                                    </li>

                                @endforeach
                            </ul>
                        </li>

                    </ul>
                </li>

                <!-- Messages: style can be found in dropdown.less-->
                @can('switch_account', \App\Models\Admin\Account::class)
                    <li class="dropdown messages-menu">
                        <a href="#" class="dropdown-warning" data-toggle="dropdown">
                            <i class="fa fa-users"></i>
                            &nbsp;
                            <span class="hidden-xs">{{ Auth::user()->account->name }}</span>
                        </a>
                        <ul class="dropdown-menu" style="min-width: auto !important;">
                            <li>
                                <ul class="menu">
                                    @foreach(\App\Models\Admin\Account::getUnselected() as $account)
                                        <li style="width: auto;">
                                            <a href="{{ route('admin.accounts.switch-account', [$account]) }}">
                                                <span class="title">{{ $account->name }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        </ul>
                    </li>
                @endcan

                <li class="dropdown user user-menu">
                    @php
                        $profileImage = Auth::user()->getMedia('profile-image')
                    @endphp

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        {{ html()->img(count($profileImage) ? $profileImage[0]->getUrl() : asset('images/admin-panel/profile-placeholder.png'))->class('user-image') }}

                        <span class="hidden-xs">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            {{ html()->img(count($profileImage) ? $profileImage[0]->getUrl() : asset('images/admin-panel/profile-placeholder.png'))->class('img-circle') }}
                            <p>
                                {{ Auth::user()->name }}
                                <small>
                                    {{ implode(', ', app()->make('loggedUserRolesNames')) }}
                                </small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ route('admin.profile.edit') }}" class="btn btn-default btn-flat">
                                    @lang('Profile')
                                </a>
                            </div>
                            <div class="pull-right">
                                <a  href="{{ route('logout') }}" class="btn btn-default btn-flat">
                                    @lang('Logout')
                                </a>
                            </div>
                        </li>
                        @can('link_profiles', \App\Models\Admin\User::class)
                            @php $linkedProfiles = \App\Models\Admin\UserLinkedProfile::getLinkedProfiles(true); @endphp
                            @if (count($linkedProfiles))
                            <!-- Menu Body -->
                            <li class="user-body">
                                <div class="row">
                                    @foreach($linkedProfiles as $link)
                                        <div class="col-xs-12 text-center" style="margin: 5px 0 5px 0;">
                                            @if ($link->user_id != Auth::id())
                                                <a href="{{ route('admin.profile.switch-linked-profile', ['linked_user_id' => $link->user_id]) }}">{{ $link->user_name . ' ' . $link->user_surname . ' (' . $link->user_account_name . ')' }}</a>
                                            @else
                                                <a href="{{ route('admin.profile.switch-linked-profile', ['linked_user_id' => $link->linked_user_id]) }}">{{ $link->linked_user_name . ' ' . $link->linked_user_surname . ' (' . $link->linked_user_account_name . ')' }}</a>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                <!-- /.row -->
                            </li>
                            @endif
                        @endcan
                    </ul>
                </li>

                @can('impersonate_back', \App\Models\Admin\User::class)
                <li class="dropdown bg-red">
                    <a href="{{ route('admin.users.impersonate_back') }}" class="dropdown-warning impersonate_back-btn" data-url="{{ route('admin.ajax.profile.show.settings', ['user_id' => session()->get('impersonated_by'),  'settings_key' => 'localstorageItems']) }}">
                        <i class="fa fa-sign-out"></i>
                    </a>
                </li>
                @endif
            </ul>
    </nav>
</header>
