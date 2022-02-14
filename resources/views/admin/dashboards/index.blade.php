@extends('layouts.admin')

@section('title', App\Models\Admin\Dashboard::getTitleTrans())

@section('content')

    @include('partials._content-heading', ['title' => App\Models\Admin\Dashboard::getTitleTrans()])

    @include('partials._alerts')

    @include('admin.datatables._datatable', [
        'dataTableObject' => $dataTableObject,
        'permissionClass' => \App\Models\Admin\Dashboard::class,
        'routeNamespace' => 'dashboards'
    ])

@stop


