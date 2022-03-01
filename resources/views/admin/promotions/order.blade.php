@extends('layouts.admin')

@section('title', App\Models\Admin\Promotion::getTitleTrans())

@section('content')

    @include('partials._content-heading', ['title' => App\Models\Admin\Promotion::getTitleTrans()])

    @include('partials._alerts')

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-10">
                    @foreach($promotions as $p)
                        <div class="alert alert-success" role="alert" style=" height: 72px ;border: 4px solid #00a65a !important;background-color: transparent !important;color: black !important;">
                            <p class="pull-left">{{$p->nome}} </p> <p class="pull-right">{{$p->datafine}}</p>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

@stop


