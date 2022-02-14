<!DOCTYPE html>
<html lang="{{ App::getLocale() }}" class="page @php
    $class = "page-";
    foreach($view_path_array as $view_path){
        $class .= "-".$view_path;
        echo $class.(next($view_path_array)?" ":"");
    }
@endphp">

<head>
    @include('partials._head')
</head>

<body class="page-header-fixed">

<div id="preloader"><div id="status"></div></div>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <div class=" main-logo">
                {{ html()->img(asset('images/admin-panel/logo.png'))->class('logo img-responsive wow zoomIn') }}
            </div>
        </div>
    </div>
    @yield('content')
    <p class="text-center copyright">
       {{ config('main.app.credits') }}
    </p>
</div>

@include('partials._javascripts-lang')

<script src="{{ mix('js/app.js') }}"></script>

@include('partials._javascripts')

</body>
</html>