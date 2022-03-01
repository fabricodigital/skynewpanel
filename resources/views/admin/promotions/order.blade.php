@extends('layouts.admin')

@section('title', App\Models\Admin\Promotion::getTitleTrans())

@section('content')

    @include('partials._content-heading', ['title' => App\Models\Admin\Promotion::getTitleTrans()])

    @include('partials._alerts')

   

@stop


