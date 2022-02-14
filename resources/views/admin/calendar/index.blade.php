@extends('layouts.admin')

@section('title', __('Calendar'))

@section('content')
    @include('partials._content-heading', ['title' => __('Calendar')])

    @include('widgets.calendar')

@stop
