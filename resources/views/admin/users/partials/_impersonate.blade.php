@can('impersonate', $user)
    {{ html()->modelForm($user, 'POST', route('admin.users.impersonate', [$user]))->style('display: inline-block')->open() }}
    {{ html()->submit(__('Impersonate'))->class('btn btn-info impersonate_btn')->data('store_settings_url', route('admin.ajax.profile.store.settings')) }}
    {{ html()->form()->close() }}
@endcan
