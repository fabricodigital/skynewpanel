@extends('layouts.admin')

@section('title', App\Models\Admin\Note::getTitleTrans())

@section('content')

    @include('partials._content-heading', ['title' => App\Models\Admin\Note::getTitleTrans()])

    @include('partials._alerts')

    @include('admin.datatables._datatable', [
        'dataTableObject' => $dataTableObject,
        'permissionClass' => \App\Models\Admin\Note::class,
        'routeNamespace' => 'notes'
    ])

@stop


