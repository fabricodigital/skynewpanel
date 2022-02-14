@extends('layouts.admin')

@section('title', App\Models\Admin\Account::getTitleTrans())

@section('content')

    @include('partials._content-heading', ['title' => App\Models\Admin\Account::getTitleTrans()])

    @include('partials._alerts')

    @include('admin.datatables._datatable', [
        'dataTableObject' => $dataTableObject,
        'permissionClass' => \App\Models\Admin\Account::class,
        'routeNamespace' => 'accounts'
    ])

@stop


