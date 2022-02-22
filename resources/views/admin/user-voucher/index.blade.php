@extends('layouts.admin')

@section('title', __('Search'))

@section('content')
@include('partials._content-heading', ['title' => __('Search')])

@include('partials._alerts')

<div class="row ">
    <div class="col-md-12">
        <form class="" action="#" method="get">
            <div class="form-group row text-center">
                <input class="form-control" type="text" name="search" required/>
            </div>
            <div class="form-group row text-center">
                <button class="form-control btn btn-success" type="submit">Search</button>
            </div>
        </form>
    </div>
</div>

@stop




