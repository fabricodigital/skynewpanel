@extends('layouts.auth')

@section('title', __('Account activation'))

@section('content')
    <div class="row">
        <div class="col-xs-12">   
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4 class="text-uppercase font-bold text-center mt-20">@lang('Account activation')</h4>
                    @include('partials._alerts')

                    {{ html()->form('POST', route('account.send-activation-link'))->novalidate()->open() }}
                    <div class="form-group">
                        <div class="col-md-12">
                            {{ html()->email('email')->class('form-control')->attributes(['autocomplete' => 'off', 'placeholder' => __('email-form-label') ]) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            {{ html()->submit(__('Submit'))->class('btn btn-block btn-success') }}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12 text-center">
                            <a href="{{ route('login') }}">
                                @lang('Already have an active account? Login here.')
                            </a>
                        </div>
                    </div>
                    @include('partials._auth-pages-lang-switcher')
                    {{ html()->form()->close() }}
                </div>
            </div>
        </div>
    </div>
@stop