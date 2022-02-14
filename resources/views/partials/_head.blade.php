<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>@yield('title') - {{ config('app.name') }}</title>

<link rel="shortcut icon" href="{{ asset('images/admin-panel/favicon-16x16.png') }}" />
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/admin-panel/apple-touch-icon.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/admin-panel/favicon-32x32.png?v=2') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/admin-panel/favicon-16x16.png?v=2') }}">

<link rel="stylesheet" href="{{ asset(mix('css/app.css')) }}" />
<link rel="stylesheet" href="{{ asset('homepagecss/css/style-index.css') }}" />
<link rel="stylesheet" href="{{ asset('css/daterangepicker.css') }}" />
<link rel="stylesheet" href="{{ asset('css/drag-drop.css') }}" />
<link rel="stylesheet" href="{{ asset('css/custom.min.css') }}" />
<link rel="stylesheet" href="{{ asset('homepagecss/css/loading.css') }}" />
<link rel="stylesheet" href="{{ asset('homepagecss/css/jquery.dataTables.css') }}" />
@yield('custom-css')
