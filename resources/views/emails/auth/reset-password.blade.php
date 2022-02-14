@extends('emails.layouts.auth')

@section('content')

    <h2>@lang('Hello!')</h2>

    <p>@lang('You are receiving this email because we received a password reset request for your account.')</p>

    <p>
        <a href="{{ route('password.reset', ['token' => $token]) }}"
           target="_blank"
           style="box-sizing: border-box; border-radius: 3px; box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16); color: #FFF; display: inline-block;
           text-decoration: none; -webkit-text-size-adjust: none; background-color: #3097D1;
           border-top: 10px solid #3097D1; border-right: 18px solid #3097D1; border-bottom: 10px solid #3097D1; border-left: 18px solid #3097D1;">
            @lang('Reset password')
        </a>
    </p>

    <p>@lang('If you did not request a password reset, no further action is required.')</p>

    <p>
        @lang('Regards'),<br>
        {{ config('app.name') }}
    </p>

@stop

@section('footer')
    <p>
        @lang("If you're having trouble clicking the button, copy and paste the URL below into your web browser:")
        <br>
        <a href="{{ route('password.reset', ['token' => $token]) }}" target="_blank">{{ route('password.reset', ['token' => $token]) }}</a>
    </p>
@stop
