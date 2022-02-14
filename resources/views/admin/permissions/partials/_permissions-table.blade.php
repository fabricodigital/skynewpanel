<table class="table permissions__table table-bordered">
    <thead>
        <tr>
            <th colspan="2" class="v-sticky v-sticky--1"></th>
            <th colspan="{{ count($roles) }}" class="v-sticky v-sticky--1">
                <span class="h-sticky h-sticky--3">@lang('Roles')</span>
            </th>
        </tr>
        <tr>
            <th colspan="2" class="h-sticky h-sticky--1 v-sticky v-sticky--2">@lang('Sections')</th>
            @foreach($roles as $role)
                <td class="v-sticky v-sticky--2">{{ $role->role_name }}</td>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($sections as $section)
            @isset($section["children"])
                <tr>
                    <th rowspan='{{ count($section["children"]) }}' class="h-sticky h-sticky--1">
                        <span class="v-sticky v-sticky--1">{{ eval_helper($section['label']) }}</span>
                    </th>
                    <th class="h-sticky h-sticky--2">
                        <span class="v-sticky v-sticky--1">{{ eval_helper($section['children'][0]['label']) }}</span>
                    </th>
                    @foreach($roles as $role)
                        <td>
                            @php
                                $permissionTarget = $section['children'][0]['permission_target'];
                            @endphp

                            <ul class="list-unstyled">
                                @foreach($section['children'][0]['permissions'] as $permission => $permissionLabel)
                                    <li>
                                        <input
                                                type="checkbox"
                                                name="role_permission"
                                                value="{{ "$permission $permissionTarget" }}"
                                                @if(!!$role->permissions->filter(function ($permissionItem) use($permission, $permissionTarget) {
                                                    return $permissionItem->name == "$permission $permissionTarget";
                                                })->count()) checked="checked" @endif
                                                id="{{ $permission }}_{{ $permissionTarget }}_{{ $role->id }}"
                                                class="permissions_checkbox"
                                                data-revoke_route="{{ route('admin.ajax.roles.revoke-permission', [$role]) }}"
                                                data-give_route="{{ route('admin.ajax.roles.give-permission', [$role]) }}"
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
                    <tr>
                        <th class="h-sticky h-sticky--2">
                            <span class="v-sticky v-sticky--1">{{ eval_helper($sectionChild['label']) }}</span>
                            </th>
                        @foreach($roles as $role)
                            <td>
                                @php
                                    $permissionTarget = $sectionChild['permission_target'];
                                @endphp

                                <ul class="list-unstyled">
                                    @foreach($sectionChild['permissions'] as $permission => $permissionLabel)
                                        <li>
                                            <input
                                                    type="checkbox"
                                                    name="role_permission"
                                                    value="{{ "$permission $permissionTarget" }}"
                                                    @if(!!$role->permissions->filter(function ($permissionItem) use($permission, $permissionTarget) {
                                                        return $permissionItem->name == "$permission $permissionTarget";
                                                    })->count()) checked="checked" @endif
                                                    id="{{ $permission }}_{{ $permissionTarget }}_{{ $role->id }}"
                                                    class="permissions_checkbox"
                                                    data-revoke_route="{{ route('admin.ajax.roles.revoke-permission', [$role]) }}"
                                                    data-give_route="{{ route('admin.ajax.roles.give-permission', [$role]) }}"
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
                <tr>
                    <th colspan="2" class="h-sticky h-sticky--1">
                        <span class="v-sticky v-sticky--1">{{ eval_helper($section['label']) }}</span>
                    </th>
                    @foreach($roles as $role)
                        <td>
                            @php
                                $permissionTarget = $section['permission_target'];
                            @endphp

                            <ul class="list-unstyled">
                                @foreach($section['permissions'] as $permission => $permissionLabel)
                                    <li>
                                        <input
                                                type="checkbox"
                                                name="role_permission"
                                                value="{{ "$permission $permissionTarget" }}"
                                                @if(!!$role->permissions->filter(function ($permissionItem) use($permission, $permissionTarget) {
                                                    return $permissionItem->name == "$permission $permissionTarget";
                                                })->count()) checked="checked" @endif
                                                id="{{ $permission }}_{{ $permissionTarget }}_{{ $role->id }}"
                                                class="permissions_checkbox"
                                                data-revoke_route="{{ route('admin.ajax.roles.revoke-permission', [$role]) }}"
                                                data-give_route="{{ route('admin.ajax.roles.give-permission', [$role]) }}"
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

            togglePermission(route, permission);
        });

        $(permissionsCheckboxes).on('ifChecked', function () {
            var route = $(this).data('give_route');
            var permission = $(this).val();

            togglePermission(route, permission);
        });*/
    });
</script>
