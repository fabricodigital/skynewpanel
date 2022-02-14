@extends('layouts.admin')

@section('title', App\Models\Admin\Notification::getTitleTrans())

@section('content')
    @include('partials._content-heading', ['title' => App\Models\Admin\Notification::getTitleTrans()])

    @include('partials._alerts')

    @include('admin.datatables._datatable', [
        'dataTableObject' => $dataTableObject,
        'permissionClass' => \App\Models\Admin\Notification::class,
        'routeNamespace' => 'notifications'
    ])

@stop
