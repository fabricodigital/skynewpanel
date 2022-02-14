@switch($state)
    @case('activated')
        <span class="label label-success">{{ \App\Models\Admin\User::getEnumsTrans('state', $state) }}</span>
    @break
    @case('deactivated')
        <span class="label label-danger">{{ \App\Models\Admin\User::getEnumsTrans('state', $state) }}</span>
    @break
    @default
        <span class="label label-info">{{ \App\Models\Admin\User::getEnumsTrans('state', $state) }}</span>
    @break
@endswitch
