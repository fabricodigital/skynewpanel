@extends('layouts.admin')

@section('title', __('Search'))

@section('content')
@include('partials._content-heading', ['title' => __('Search')])

@include('partials._alerts')

<div class="panel panel-default">
    <div class="panel-body">
        <div class="row ">
            <div class="col-md-12">
                <form class="" action="{{route('admin.searchclient')}}" method="POST">
                    {!! csrf_field() !!}
                    @include('partials.inputs._text', [
                  'name' => 'infocode',
                  'label' => __('name').'*',
                  'width' => 8,
                  'attributes' => ['maxlength' => 25],
              ])
                    <div class="col-md-5 form-group ">
                        <button class="form-control btn btn-success" type="submit">Search</button>

                </form>
            </div>
        </div>
    </div>
</div>

@stop




