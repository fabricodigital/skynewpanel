@extends('layouts.admin')

@section('title', __('Audit log'))

@section('content')
    @include('partials._content-heading', ['title' => App\Models\Admin\Revision::getTitleTrans()])

    @include('partials._alerts')

    @include('admin.datatables._datatable', [
        'dataTableObject' => $dataTableObject,
        'permissionClass' => \App\Models\Admin\Revision::class,
        'routeNamespace' => 'revisions'
    ])

@stop


