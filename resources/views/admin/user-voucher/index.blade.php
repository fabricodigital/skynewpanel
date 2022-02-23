@extends('layouts.admin')

@section('title', __('Search'))

@section('content')
@include('partials._content-heading', ['title' => __('Search')])

@include('partials._alerts')

<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="">
                <form class="col-md-6 col-md-offset-3" action="{{route('admin.searchclient')}}" method="POST">
                    @csrf
                    @include('partials.inputs._text', [
                        'name' => 'infocode',
                        'label' => '',
                        'width' => 10,
                        'attributes' => ['maxlength' => 25],
                    ])
                    <div class="col-md-6 col-md-offset-2 form-group ">
                        <button class="form-control btn btn-success" type="submit">Search</button>
                    </div>
                </form>
            </div>
        </div>
        @isset($uservouch)
            @include('admin.user-voucher.partials._tables-result')
        @endisset
    </div>
</div>

@stop




