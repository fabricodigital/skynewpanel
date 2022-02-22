@extends('layouts.admin')

@section('title', __('Search'))

@section('content')
@include('partials._content-heading', ['title' => __('Search')])

@include('partials._alerts')

<div class="panel panel-default">
    <div class="panel-body">
        <div class="row ">
            <div class="col-md-12">
                <form class="" action="#" method="get">
                    @include('partials.inputs._text', [
                  'name' => 'name',
                  'label' => __('name').'*',
                  'width' => 6,
                  'attributes' => ['maxlength' => 25],
              ])
                    <div class="form-group row text-center">
                        <button class="form-control btn btn-success" type="submit">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@stop




