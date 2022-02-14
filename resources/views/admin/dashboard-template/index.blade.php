<!DOCTYPE html>
<html class="no-js">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

    <!-- Fonts -->
    <!-- Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Droid+Serif:400i|Source+Sans+Pro:300,400,600,700"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300,400,600,700" rel="stylesheet">

    <!-- CSS -->
    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="{{ asset('homepagecss/plugins/bootstrap/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('homepagecss/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/drag-drop.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/custom.min.css') }}" />
</head>

<body id="body">
    <!-- navigation -->
    <div class="content">
        <header>
            <nav class="navbar navbar-expand-lg navbar-light bg-white">
                <div class="container">
                    <div class="container-logo">
                        <a class="navbar-brand" href="{{ route('admin.homepage') }}"><img src="{{ $logo }}"></a>
                    </div>

                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ml-auto text-center text-lg-left widgets-nav-wrapper">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.homepage') }}">{{ __('Home') }}
                                    <ion-icon name="home-outline"></ion-icon>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">{{ __('Templates') }}
                                    <ion-icon name="add-circle-outline"></ion-icon>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.home') }}">{{ __('Configurator') }}
                                    <ion-icon name="settings-outline"></ion-icon>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.faq') }}">{{ __('FAQ') }}
                                    <ion-icon name="information-circle-outline"></ion-icon>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('logout') }}">{{ __('Logout') }}
                                    <ion-icon name="log-out-outline"></ion-icon>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <div class="custom-container">
            <div class="grid-stack drag-container widget-container">
                <div class="row">
                    <div class="col-md-8 offset-md-2 mt-4">
                        <div class="row">
                            @foreach($dashboards as $dashboard)
                                <div class="col-sm-12 col-md-12 col-lg-4 mb-4">
                                <div class="card" data-card="{{ $dashboard->id }}" id="dashboard-{{ $dashboard->id }}">
                                    <img class="card-img-top" src="{{ $dashboard->getMedia('dashboard_image')->isNotEmpty() ? $dashboard->getMedia('dashboard_image')[0]->getFullUrl() : 'https://fakeimg.pl/270x200/?text=No%20image' }}" alt="{{ $dashboard->name }}">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $dashboard->name }}</h5>
                                        <p class="card-text">{{ $dashboard->description }}</p>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-12 col-lg-6 mt-1">
                                                <a class="btn btn-secondary btn-sm btn-group d-flex justify-content-center" href="{{ route('admin.dashboard.show', $dashboard->id) }}" target="_blank" rel="noopener noreferrer">{{ __('Preview') }}</a>
                                            </div>
                                            <div class="col-sm-12 col-md-12 col-lg-6 mt-1" data-card="{{ $dashboard->id }}">
                                                <a class="btn btn-secondary btn-sm btn-group d-flex justify-content-center add-widget {{ $user->dashboards()->where('id', '=', $dashboard->id)->exists()? 'active' : '' }}" href="#dashboard-{{ $dashboard->id }}"> {{ $user->dashboards()->where('id', '=', $dashboard->id)->exists()? __('Remove') : __('Add') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-12">
                    <div class="block">
                        <img src="http://skynewpanel.fabricandum.com/images/admin-panel/logo.png" width="250">

                        <p class="copyright-text">Copyright &copy; <a href="#">Sky </a>|
                            All right reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        $(document).ready(function(){

            $('.add-widget').click(function(element){
                let dashboard_id = $(this).parent().data('card');
                let add_dashboard = false;

                if($(this).hasClass('active')){
                    $(this).removeClass('active');
                    $(this).text("{{ __('Add') }}");
                    add_dashboard = 'remove';
                } else {
                    $(this).addClass('active');
                    $(this).text("{{ __('Remove') }}");
                    add_dashboard = 'add';
                }

                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: "{{ route('admin.dashboard.synctouser') }}",
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        _token: CSRF_TOKEN,
                        _method: "POST",
                        uid : "{{ auth()->user()->password .''. auth()->user()->id }}",
                        dashboardId : dashboard_id,
                        addDashboard: add_dashboard,
                    },
                    success: function (response) {
                        console.log(response);
                    },
                    error: function(response){
                        console.log(response);
                    }
                });
            });
        });
    </script>
</body>

</html>
