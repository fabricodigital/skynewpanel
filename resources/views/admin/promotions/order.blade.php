@extends('layouts.admin')

@section('title', $promotion::getMsgTrans('view_heading'))

@section('content')
    @include('partials._content-heading', ['title' => $promotion::getMsgTrans('view_heading'), 'deleted' => !empty($promotion->deleted_at) ? true : false])

    <div class="panel panel-default{{ !empty($promotion->deleted_at) ? ' record-deleted' : '' }}">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">

                </div>
            </div>
        </div>
    </div>

    <div class="pull-left">

    </div>

    <div class="pull-right">
        <a href="{{ route('admin.promotions.index') }}" class="btn btn-primary">@lang('Back')</a>
    </div>

    <div class="clearfix"></div>



@stop

