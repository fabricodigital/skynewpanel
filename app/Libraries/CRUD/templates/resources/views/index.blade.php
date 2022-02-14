@extends('layouts.admin')

@section('title', App\Models\Admin\CRUD_filename::getTitleTrans())

@section('content')

    @include('partials._content-heading', ['title' => App\Models\Admin\CRUD_filename::getTitleTrans()])

    @include('partials._alerts')

    @include('admin.datatables._datatable', [
        'dataTableObject' => $dataTableObject,
        'permissionClass' => \App\Models\Admin\CRUD_filename::class,
        'routeNamespace' => 'CRUD_route'
    ])

@stop


