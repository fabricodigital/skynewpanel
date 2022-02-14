@extends('layouts.admin')

@section('title', __('Permissions'))

@section('content')
    @include('partials._content-heading', ['title' => App\Models\Admin\Permission::getTitleTrans()])

    <div class="panel panel-default">
        <div class="panel-body">
            @include('admin.permissions.partials._permissions', ['sections' => $sections, 'roles' => $roles])
        </div>
    </div>
@stop
