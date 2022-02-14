{{ html()->modelForm($user, 'PUT', route('admin.profile.update', [$user]))->id('profile_form')->open() }}
<div class="row">
    <div class="col-md-4 form-group text-center">
        <div class="form-group">
            <div class="avatar">
                <div class="avatar__border">
                    <div class="avatar__canvas">
                        {{ html()->img(count($profileImage) ? $profileImage[0]->getUrl() : asset('images/admin-panel/profile-placeholder.png'))->class('img-responsive')->id('profile-preview-img') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group profile">
            <div class="btn btn-default btn-file">
                <i class="fa fa-paperclip"></i> @lang('Avatar')
                <input type="file"
                       name="image"
                       class="btn file-upload">
            </div>
        </div>
    </div>

    @include('partials.inputs._email', ['name' => 'email', 'label' => $user::getAttrsTrans('email').'*', 'width' => 4])

    <div class="col-md-4 form-group">
        {{ html()->label($user::getAttrsTrans('locale').'*', 'locale')->class('control-label') }}
        <select name="locale" id="locale" class="select2-with-flag" data-placeholder="@lang('Select...')">
            @foreach(config('main.available_languages') as $abbr => $label)
                <option value="{{ $abbr }}" @if($user->locale == $abbr) selected="selected" @endif data-flag="{{ $abbr != 'en' ? $abbr : 'gb' }}">{{ __($label) }}</option>
            @endforeach
        </select>
    </div>

    @include('partials.inputs._password', ['name' => 'password', 'label' => $user::getAttrsTrans('password').'*', 'width' => 4])

    <div class="col-md-4 form-group @if($errors->has('password_confirmation')) has-error @endif">
        {{ html()->label(__('password_confirmation-form-label'), 'password_confirmation')->class('control-label') }}
        {{ html()->password('password_confirmation')->class('form-control') }}
        @include('partials._field-error', ['field' => 'password_confirmation'])
    </div>
</div>
<div class="row">
    @include('partials.inputs._text', ['name' => 'name', 'label' => $user::getAttrsTrans('name').'*', 'width' => 6])

    @include('partials.inputs._text', ['name' => 'surname', 'label' => $user::getAttrsTrans('surname').'*', 'width' => 6])
</div>
@can('view_sensitive_data', \App\Models\Admin\User::class)
    <div class="row">
        @include('partials.inputs._select-multi', [
            'name' => 'roles',
            'label' => $user::getAttrsTrans('roles').'*',
            'width' => 8,
            'options' => $roles,
            'selected' => $user->roles()->pluck('id')->toArray(),
            'multiple' => true,
            'disabled' => !Auth()->user()->can('assign_roles', \App\Models\Admin\User::class)
        ])

        @include('partials.inputs._select', [
            'name' => 'state',
            'label' => $user::getAttrsTrans('state').'*',
            'width' => 4,
            'options' => ['' => ''] + $user::getEnumsTrans('state'),
            'disabled' => !Auth()->user()->can('change_state', \App\Models\Admin\User::class)
        ])
    </div>
@endcan

<div class="row">
    <div class="col-xs-12 form-group" style="margin-top: 20px;">
        <div class="pull-left">
            {{ html()->submit(__('Save'))->class('btn btn-success update_profile_btn') }}
        </div>
    </div>
</div>
{{ html()->form()->close() }}
