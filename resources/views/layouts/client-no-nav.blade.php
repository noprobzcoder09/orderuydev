<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="@yield('title')">
    <meta name="keyword" content=",">
    <meta name="meta-csrf" content="{{ csrf_token() }}">
    <title>Ultimate You Fuel @yield('page-title')</title>


    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('images/favicons/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('images/favicons/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('images/favicons/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('images/favicons/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('images/favicons/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('images/favicons/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('images/favicons/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('images/favicons/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicons/apple-icon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('images/favicons/cropped-icon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicons/cropped-icon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('images/favicons/cropped-icon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicons/cropped-icon-32x32.png') }}">
        <link rel="manifest" href="{{ asset('images/favicons/manifest.json') }}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('images/favicons/ms-icon-144x144.png') }}">
        <meta name="theme-color" content="#ffffff">
    <!-- Icons -->
    <link href="{{ asset('vendors/css/flag-icon.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/css/simple-line-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/css/iziToast.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/css/prettify.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <!-- Main styles for this application -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

    <style type="text/css">
        #top-links li a {
            font-size: 12px;    
            color: #fff;
        }
        #top-links li {
            font-size: 12px;    
            color: #fff;
            padding-left: 10px;
        }
        #top-links li:first-child {
            margin-right: 10px;
        }
        #top-links li:nth-child(2) > a, #top-links li:nth-child(3) > a, #top-links li:nth-child(4) > a {
            text-transform: none !important;
        }

        .main-menu-items li a {
            text-transform: uppercase !important;
        }
    </style>
    @yield('css')

</head>
<body class="app">
    <div id="topnav">
        <div id="topnav-container">
            @if(Auth::check())
            <div class="row">
                <div class="col-md-12">
                    <ul class="main-menu-items pull-right" id="top-links">
                        <li>Hi {{(new \App\Repository\UsersRepository)->getFirstName()}},</li>
                        <li><a href="{{url('customers/logout')}}">Logout</a></li>
                    </ul>
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-6">

                    <a class="navbar-brand-v2" href="https://ultimateyoufuel.com"><img src="{{ asset('images/logo.png') }}" width="258" height="43"></a>
      
                </div>
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-6">
                    <ul class="main-menu-items responsive-override">
                        <li class="nav-item">
                            <a class="nav-link" href="https://ultimateyoufuel.com">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://ultimateyoufuel.com/meals">Meals</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://ultimateyoufuel.com/about-us">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://ultimateyoufuel.com/contact-us">Contact Us</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>  
    </div>
    <div id="site-container">
        <div class="contents">
            <div class="container">
                @yield('content')
            </div>
        </div>
    </div>
    @include('pages.client.footer')

    <!-- Bootstrap and necessary plugins -->
    <script src="{{asset('vendors/js/jquery.min.js')}}"></script>
    <script src="{{asset('vendors/js/popper.min.js')}}"></script>
    <script src="{{asset('vendors/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('vendors/js/pace.min.js')}}"></script> 
    <script src="{{asset('vendors/js/blockui.js')}}"></script>  
    <script src="{{asset('vendors/js/iziToast.min.js')}}"></script> 

    <!-- CoreUI Pro main scripts -->
    <script src="{{asset('js/app.js')}}"></script>  
    <script src="{{asset('js/global.js')}}"></script>   
    <script src="{{asset('js/system.js')}}"></script>   
    <script src="{{asset('js/alert.js')}}"></script>    
    <script src="{{asset('vendors/js/jquery.bootstrap.wizard.min.js')}}"></script> 
    <script src="{{asset('vendors/js/prettify.js')}}"></script> 

    @yield('script')
</body>
</html>

