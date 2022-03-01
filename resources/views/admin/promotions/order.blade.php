@extends('layouts.admin')

@section('title', App\Models\Admin\Promotion::getTitleTrans())

@section('content')

    @include('partials._content-heading', ['title' => App\Models\Admin\Promotion::getTitleTrans()])

    @include('partials._alerts')

    <div class="panel panel-default">
        <div class="panel-body">
            @foreach($promotions as $p)
                <div class="alert alert-success" role="alert">
                    <p>{{$p->nome}}</p>
                </div>
            @endforeach
        </div>
    </div>

@stop


