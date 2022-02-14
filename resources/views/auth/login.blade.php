@extends('layouts.auth')

@section('title', __('Login'))

@section('content')
<div class="row">
    <div class="col-xs-12">      
        <div class="panel panel-default">
            <div class="panel-body">
                <h4 class="text-uppercase font-bold text-center mt-20">@lang('Login')</h4>
                @include('partials._alerts')

                {{ html()->form('POST', route('login'))->novalidate()->open() }}
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </span>
                            {{ html()->email('email')->class('form-control')->attributes(['autocomplete' => 'off', 'placeholder' => __('email-form-label') ]) }}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-lock"></i>
                            </span>
                            {{ html()->password('password')->class('form-control')->attributes(['autocomplete' => 'off', 'placeholder' => __('password-form-label') ]) }}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        {{ html()->submit(__('Login'))->class('btn btn-block btn-success') }}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <span class="remember">
                            {{ html()->checkbox('remember', false, 1)->class('ios-checkbox')->attributes(['autocomplete' => 'off']) }}
                            {{ html()->label(__('Remember me'), 'remember') }}
                        </span>
                        <span class="auth-links">
                            <a href="{{ route('password.request') }}">@lang('Forgot your password')</a> | 
                            <a href="{{ route('account.request-activation-link') }}">@lang('Account activation')</a>
                        </span>
                    </div>
                </div>
                @include('partials._auth-pages-lang-switcher')
                {{ html()->form()->close() }}
            </div>
        </div>
    </div>
</div>
@stop