@extends('layouts.admin')

@section('title', App\Models\Admin\FaqCategory::getTitleTrans())

@section('content')

    @include('partials._content-heading', ['title' => App\Models\Admin\FaqCategory::getTitleTrans()])

    @include('partials._alerts')

    @include('admin.datatables._datatable', [
        'dataTableObject' => $dataTableObject,
        'permissionClass' => \App\Models\Admin\FaqCategory::class,
        'routeNamespace' => 'faq-categories'
    ])

@stop


