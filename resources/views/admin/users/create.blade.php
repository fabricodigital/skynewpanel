@extends('layouts.admin')

@section('title', $user::getMsgTrans('create_heading'))

@section('content')
@include('partials._content-heading', ['title' => $user::getMsgTrans('create_heading')])

@include('partials._alerts')

{{ html()->form('POST', route('admin.users.store'))->class('')->acceptsFiles()->open() }}

<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4 form-group text-center" style="min-height: 150px;">
                <ul class="list-group preview-container avatar-preview-container">

                </ul>
                @include('partials.inputs._file-upload', ['name' => 'image', 'label' => __('profile-image-form-label'), 'previewContainer' => 'avatar-preview-container', 'multiple' => false])
            </div>

            @include('partials.inputs._email', ['name' => 'email', 'label' => $user::getAttrsTrans('email').'*', 'width' => 4])

            <div class="col-md-4 form-group">
                {{ html()->label($user::getAttrsTrans('locale').'*', 'locale')->class('control-label') }}
                <select name="locale" id="locale" class="select2-with-flag" data-placeholder="@lang('Select...')">
                    @foreach(config('main.available_languages') as $abbr => $label)
                        <option value="{{ $abbr }}" @if(old('locale') && old('locale') == $abbr) selected="selected" @endif data-flag="{{ $abbr != 'en' ? $abbr : 'gb' }}">{{ __($label) }}</option>
                    @endforeach
                </select>
            </div>

            @include('partials.inputs._password', ['name' => 'password', 'label' => $user::getAttrsTrans('password').'*', 'width' => 4])

            <div class="col-md-4 form-group @if($errors->has('password')) has-error @endif">
                {{ html()->label(__('password_confirmation-form-label').'*', 'password_confirmation')->class('control-label') }}
                {{ html()->password('password_confirmation')->class('form-control') }}
                @include('partials._field-error', ['field' => 'password'])
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
                    'multiple' => true
                ])

                @include('partials.inputs._select', [
                    'name' => 'state',
                    'label' => $user::getAttrsTrans('state').'*',
                    'width' => 4,
                    'options' => ['' => ''] + $user::getEnumsTrans('state'),
                ])
            </div>
        @endcan
    </div>
</div>

<div class="pull-left">
    @include('partials._submit-crud-form-action-btn')
</div>

<div class="pull-right">
    <a href="{{ route('admin.users.index') }}" class="btn btn-primary">@lang('Back')</a>
</div>
{{ html()->form()->close() }}
@stop


