@extends('emails.layouts.admin')

@section('content')

    <h2>@lang('Hello!')</h2>

    <p>@lang('A failure occurred during a background process execution:')</p>

    <p>
    <ul style="list-style-type: none">
        <li>
            <strong>@lang('DATE START'):</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
        </li>
        <li>
            <strong>@lang('MESSAGE'):</strong>
            {{ $jobsLog->exception->getMessage().PHP_EOL }}
            {{ $jobsLog->exception->getFile().PHP_EOL }}
            {{ $jobsLog->exception->getLine().PHP_EOL }}
            {{ $jobsLog->exception->getTraceAsString().PHP_EOL }}
            {{ $jobsLog->exception->getCode().PHP_EOL }}
        </li>
    </ul>
    </p>

    <p>
        @lang('Regards'),<br>
        {{ config('app.name') }}
    </p>

@stop


