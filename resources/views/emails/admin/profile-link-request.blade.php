@extends('emails.layouts.admin')

@section('content')

    <h2>@lang('Hello :name :surname!', ['name' => $notifiable->name, 'surname' => $notifiable->surname])</h2>

    <p>Profile link request sent from <b>{{ Auth::user()->email }}</b>.</p>

    <p>
        <a href="{{ route('account.activate-linked-profiles', ['token' => \Illuminate\Support\Facades\Crypt::encrypt($notifiable->email), 'hash' => \Illuminate\Support\Facades\Crypt::encrypt($hash)]) }}"
           target="_blank"
           style="box-sizing: border-box; border-radius: 3px; box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16); color: #FFF; display: inline-block;
           text-decoration: none; -webkit-text-size-adjust: none; background-color: #3097D1;
           border-top: 10px solid #3097D1; border-right: 18px solid #3097D1; border-bottom: 10px solid #3097D1; border-left: 18px solid #3097D1;">
            @lang('Link profiles')
        </a>
    </p>

    <p>
        @lang('Regards'),<br>
        {{ config('app.name') }}
    </p>

@stop
