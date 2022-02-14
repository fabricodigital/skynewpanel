@extends('layouts.admin')

@section('title', App\Models\Admin\Role::getTitleTrans())

@section('content')
    @include('partials._content-heading', ['title' => App\Models\Admin\Role::getTitleTrans()])

    @include('partials._alerts')

    @include('admin.datatables._datatable', [
        'dataTableObject' => $dataTableObject,
        'permissionClass' => \App\Models\Admin\Role::class,
        'routeNamespace' => 'roles'
    ])

@stop


