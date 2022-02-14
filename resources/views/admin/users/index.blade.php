@extends('layouts.admin')

@section('title', App\Models\Admin\User::getTitleTrans())

@section('content')
    @include('partials._content-heading', ['title' => App\Models\Admin\User::getTitleTrans()])

    @include('partials._alerts')

    @include('admin.datatables._datatable', [
        'dataTableObject' => $dataTableObject,
        'permissionClass' => \App\Models\Admin\User::class,
        'routeNamespace' => 'users'
    ])

@stop


