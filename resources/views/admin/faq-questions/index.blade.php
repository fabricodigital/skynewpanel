@extends('layouts.admin')

@section('title', App\Models\Admin\FaqQuestion::getTitleTrans())

@section('content')
    @include('partials._content-heading', ['title' => App\Models\Admin\FaqQuestion::getTitleTrans()])

    @include('partials._alerts')

    @include('admin.datatables._datatable', [
        'dataTableObject' => $dataTableObject,
        'permissionClass' => \App\Models\Admin\FaqQuestion::class,
        'routeNamespace' => 'faq-questions'
    ])

@stop


