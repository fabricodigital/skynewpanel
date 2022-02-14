@extends('layouts.admin')

@section('title', __('Changelog'))

@section('content')
    <h3 class="page-title"><i class="fa fa-list-ul"></i> @lang('Changelog')</h3>
   
    <div class="box box-success">
        <div class="box-header with-border">
            <h2 class="box-title">@lang('Current version'): v{{ config('main.app.version') }}</h2>
        </div>

        <div class="box-body"> 
            <p>@lang('Below you can find the changes and evolutions of the platform versions'):</p>
            
            <div style="margin-top: 20px">
                <h5>v1.0.0 (01/01/2019)</h5>
                <ul>
                    <li>Initial deploy</li>
                </ul>
            </div>
        </div>
    </div>
@stop
