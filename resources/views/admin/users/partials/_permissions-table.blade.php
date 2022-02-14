<table class="table permissions__table table-bordered">
    <thead>
         <tr>
            <th colspan="2" class="v-sticky v-sticky--1"></th>
            <th class="v-sticky v-sticky--1">@lang('User')</th>
            <th colspan="{{ count($roles) }}" class="v-sticky v-sticky--1">
                @lang('Roles')
            </th>
        </tr>
        <tr>
            <th colspan="2" class="h-sticky h-sticky--1 v-sticky v-sticky--2">@lang('Sections')</th>
            <td class="v-sticky v-sticky--2">{{ $user->name }} {{ $user->surname }} @if($user->company_name != "") ({{ $user->company_name }}) @endif</td>
            @foreach($roles as $role)
                <td data_user-role-id="{{ $role->id }}" class="v-sticky v-sticky--2">
                    {{ $role->role_name }}
                </td>
            @endforeach
        </tr>
    </thead>

    <tbody>
    @foreach($sections as $section)
        @isset($section["children"])
            @php
                $permissionTarget = $section['children'][0]['permission_target'];
            @endphp
            <tr>
                <th rowspan='{{ count($section["children"]) }}' class="h-sticky h-sticky--1">
                    <span class="v-sticky v-sticky--1">{{ eval_helper($section['label']) }}</span>
                </th>
                <th class="h-sticky h-sticky--2">
                    <span class="v-sticky v-sticky--1">{{ eval_helper($section['children'][0]['label']) }}</span>
                </th>
                <td>
                    <ul class="list-unstyled">
                        @foreach($section['children'][0]['permissions'] as $permission => $permissionLabel)
                            @php
                                $userHasPermission = $user->hasPermissionTo("$permission $permissionTarget");
                                $userHasNotPermission = $user->hasNotPermissionTo("$permission $permissionTarget");
                                $userHasPermission = $userHasPermission && !$userHasNotPermission;
                                $anyRoleHasPermission = !!$roles->filter(function ($role) use($permission, $permissionTarget) {
                                    return !!$role->permissions->filter(function ($permissionItem) use($permission, $permissionTarget) {
                                        return $permissionItem->name == "$permission $permissionTarget";
                                    })->count();
                                })->count();
                            @endphp
                            <li>
                                <input
                                        type="checkbox"
                                        name="role_permission"
                                        value="{{ "$permission $permissionTarget" }}"
                                        @if($userHasPermission) checked="checked" @endif
                                        id="{{ $permission }}_{{ $permissionTarget }}_{{ $user->id }}"
                                        class="permissions_checkbox"
                                        data-revoke_route="{{ route('admin.ajax.users.revoke-permission', [$user]) }}"
                                        data-give_route="{{ route('admin.ajax.users.give-permission', [$user]) }}"
                                >
                                <label for="{{ $permission }}_{{ $permissionTarget }}_{{ $user->id }}">{{ eval_helper($permissionLabel) }}</label>
                            </li>
                        @endforeach
                    </ul>
                </td>
                @foreach($roles as $role)
                    <td>
                        <ul class="list-unstyled">
                            @foreach($section['children'][0]['permissions'] as $permission => $permissionLabel)
                                <li>
                                    <input
                                            type="checkbox"
                                            name="role_permission"
                                            value="{{ "$permission $permissionTarget" }}"
                                            @if($role->hasPermissionTo("$permission $permissionTarget")) checked="checked" @endif
                                            id="{{ $permission }}_{{ $permissionTarget }}_{{ $role->id }}"
                                            class="permissions_checkbox"
                                            disabled="disabled"
                                    >
                                    <label for="{{ $permission }}_{{ $permissionTarget }}_{{ $role->id }}">{{ eval_helper($permissionLabel) }}</label>
                                </li>
                            @endforeach
                        </ul>
                    </td>
                @endforeach
            </tr>
            @foreach($section['children'] as $index => $sectionChild)
                @if($index == 0)
                    @continue
                @endif
                @php
                    $permissionTarget = $sectionChild['permission_target'];
                @endphp
                <tr>
                    <th class="h-sticky h-sticky--2">
                        <span class="v-sticky v-sticky--1">{{ eval_helper($sectionChild['label']) }}</span>
                    </th>
                    <td>
                        <ul class="list-unstyled">
                            @foreach($sectionChild['permissions'] as $permission => $permissionLabel)
                                @php
                                    $userHasPermission = $user->hasPermissionTo("$permission $permissionTarget");
                                    $userHasNotPermission = $user->hasNotPermissionTo("$permission $permissionTarget");
                                    $userHasPermission = $userHasPermission && !$userHasNotPermission;
                                    $anyRoleHasPermission = !!$roles->filter(function ($role) use($permission, $permissionTarget) {
                                        return !!$role->permissions->filter(function ($permissionItem) use($permission, $permissionTarget) {
                                            return $permissionItem->name == "$permission $permissionTarget";
                                        })->count();
                                    })->count();
                                @endphp
                                <li>
                                    <input
                                            type="checkbox"
                                            name="role_permission"
                                            value="{{ "$permission $permissionTarget" }}"
                                            @if($userHasPermission) checked="checked" @endif
                                            id="{{ $permission }}_{{ $permissionTarget }}_{{ $user->id }}"
                                            class="permissions_checkbox"
                                            data-revoke_route="{{ route('admin.ajax.users.revoke-permission', [$user]) }}"
                                            data-give_route="{{ route('admin.ajax.users.give-permission', [$user]) }}"
                                    >
                                    <label for="{{ $permission }}_{{ $permissionTarget }}_{{ $user->id }}">{{ eval_helper($permissionLabel) }}</label>
                                </li>
                            @endforeach
                        </ul>
                    </td>

                    @foreach($roles as $role)
                        <td>
                            <ul class="list-unstyled">
                                @foreach($sectionChild['permissions'] as $permission => $permissionLabel)
                                    <li>
                                        <input
                                                type="checkbox"
                                                name="role_permission"
                                                value="{{ "$permission $permissionTarget" }}"
                                                @if($role->hasPermissionTo("$permission $permissionTarget")) checked="checked" @endif
                                                id="{{ $permission }}_{{ $permissionTarget }}_{{ $role->id }}"
                                                class="permissions_checkbox"
                                                disabled="disabled"
                                        >
                                        <label for="{{ $permission }}_{{ $permissionTarget }}_{{ $role->id }}">{{ eval_helper($permissionLabel) }}</label>
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                    @endforeach
                </tr>
            @endforeach
        @else
            @php
                $permissionTarget = $section['permission_target'];
            @endphp
            <tr data_section="{{ $permissionTarget }}" class="user_permissions_table_section_row">
                <th colspan="2" class="h-sticky h-sticky--1">
                    <span class="v-sticky v-sticky--1">{{ eval_helper($section['label']) }}</span>
                </th>

                <td>
                    <ul class="list-unstyled">
                        @foreach($section['permissions'] as $permission => $permissionLabel)
                            @php
                                $userHasPermission = $user->hasPermissionTo("$permission $permissionTarget");
                                $userHasNotPermission = $user->hasNotPermissionTo("$permission $permissionTarget");
                                $userHasPermission = $userHasPermission && !$userHasNotPermission;
                                $anyRoleHasPermission = !!$roles->filter(function ($role) use($permission, $permissionTarget) {
                                    return !!$role->permissions->filter(function ($permissionItem) use($permission, $permissionTarget) {
                                        return $permissionItem->name == "$permission $permissionTarget";
                                    })->count();
                                })->count();
                            @endphp
                            <li>
                                <input
                                        type="checkbox"
                                        name="role_permission"
                                        value="{{ "$permission $permissionTarget" }}"
                                        @if($userHasPermission) checked="checked" @endif
                                        id="{{ $permission }}_{{ $permissionTarget }}_{{ $user->id }}"
                                        class="permissions_checkbox"
                                        data-revoke_route="{{ route('admin.ajax.users.revoke-permission', [$user]) }}"
                                        data-give_route="{{ route('admin.ajax.users.give-permission', [$user]) }}"
                                >
                                <label for="{{ $permission }}_{{ $permissionTarget }}_{{ $user->id }}">{{ eval_helper($permissionLabel) }}</label>
                            </li>
                        @endforeach
                    </ul>
                </td>

                @foreach($roles as $role)
                    <td>
                        <ul class="list-unstyled">
                            @foreach($section['permissions'] as $permission => $permissionLabel)
                                <li>
                                    <input
                                            type="checkbox"
                                            name="role_permission"
                                            value="{{ "$permission $permissionTarget" }}"
                                            @if($role->hasPermissionTo("$permission $permissionTarget")) checked="checked" @endif
                                            id="{{ $permission }}_{{ $permissionTarget }}_{{ $role->id }}"
                                            class="permissions_checkbox"
                                            disabled="disabled"
                                    >
                                    <label for="{{ $permission }}_{{ $permissionTarget }}_{{ $role->id }}">{{ eval_helper($permissionLabel) }}</label>
                                </li>
                            @endforeach
                        </ul>
                    </td>
                @endforeach
            </tr>
        @endisset
    @endforeach
    </tbody>
</table>

<script>
    $(document).ready(function () {
        var permissionsCheckboxes = $('.permissions__table input[type="checkbox"]');
        /*permissionsCheckboxes.iCheck({
            checkboxClass: 'icheckbox_square-green',
            increaseArea: '50%'
        });*/

        function togglePermission(route, permission) {
            $.ajax({
                url: route,
                method: "POST",
                data: {
                    _token: window.token.content,
                    permission: permission,
                },
                success: function (data){
                    SwalToast.fire({
                        type: 'success',
                        title: data.message
                    });
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        permissionsCheckboxes.click( function(){
            var permission = $(this).val();
            if($(this).is(':checked')) {
                var route = $(this).data('give_route');
            } else {
                var route = $(this).data('revoke_route');
            }
            togglePermission(route, permission);
        });

        /*$(permissionsCheckboxes).on('ifUnchecked', function () {
            var route = $(this).data('revoke_route');
            var permission = $(this).val();

            togglePermission(route, permission)
        });

        $(permissionsCheckboxes).on('ifChecked', function () {
            var route = $(this).data('give_route');
            var permission = $(this).val();

            togglePermission(route, permission)
        });*/
    });
</script>
