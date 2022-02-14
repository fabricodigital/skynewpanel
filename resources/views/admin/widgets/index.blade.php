@extends('layouts.admin')

@section('title', App\Models\Admin\Widget::getTitleTrans())

@section('content')

    @include('partials._content-heading', ['title' => App\Models\Admin\Widget::getTitleTrans()])

    @include('partials._alerts')

    @include('admin.datatables._datatable', [
        'dataTableObject' => $dataTableObject,
        'permissionClass' => \App\Models\Admin\Widget::class,
        'routeNamespace' => 'widgets'
    ])

@stop


