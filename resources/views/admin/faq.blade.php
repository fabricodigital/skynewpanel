<!DOCTYPE html>
<html class="no-js">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>FAQ Sky</title>
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
    <link rel="stylesheet" href="{{ asset('css/custom.min.css') }}" />
    <!-- Responsive Stylesheet -->
</head>

<body id="body">
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
                                    <ion-icon name="layers-outline"></ion-icon>
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

        <section class="accordion-section clearfix mt-5" aria-label="Question Accordions">
            <div class="container faq">
                <div class="wrapper">
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        @foreach ($faqCategories as $key_c => $category)
                        <h3 class="faq-cat-title">
                            {!! $category->title !!}
                        </h3>
                        @foreach ($category->questions as $key_q => $question)
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading{{ $key_c.'-'.$key_q}}">
                                <div class="panel-title">
                                    <a class="collapsed" data-toggle="collapse" href="#collapse{{ $key_c.'-'.$key_q}}">
                                        {!! $question->question_text !!}
                                        <ion-icon name="chevron-down-outline"></ion-icon>
                                    </a>
                                </div>
                            </div>

                            <div id="collapse{{ $key_c.'-'.$key_q}}" class="panel-collapse collapse" role="tabpanel"
                                aria-labelledby="heading{{ $key_c.'-'.$key_q}}">
                                <div class="panel-body px-3 mb-4">
                                    <p>{!! $question->answer_text !!}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
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

    <!-- Js -->
    <script src="{{ asset('homepagecss/plugins/jquery-2.1.1.min.js') }}"></script>
    <script src="{{ asset('homepagecss/plugins/bootstrap/bootstrap.min.js') }}"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>
