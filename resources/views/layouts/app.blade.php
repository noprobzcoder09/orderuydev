<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="FusedSoftware">
        <meta name="keyword" content=",">
        <meta name="meta-csrf" content="{{ csrf_token() }}">
        <title>Ultimate You Fuel - Orders</title>
        
        <!-- Added here for the Font -->
        <script src="{{ asset('/template/base/fonts/webfont.js') }}"></script>
        <script>
          WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
          });
        </script>

        <!-- Favicon -->
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
        <link href="{{ asset('vendors/css/easy-autocomplete.min.css') }}" rel="stylesheet">
        <link href="{{ asset('vendors/css/easy-autocomplete.themes.min.css') }}" rel="stylesheet">

        <!-- Main styles for this application -->
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">

        <!-- Custom styles for this application -->
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

        <!-- Fonts -->
        <link href="{{ asset('/template/base/fonts/nunito.css') }}" rel="stylesheet" type="text/css">
        
        <!-- Inserted here -->
        <!-- <link rel="stylesheet" href="{{asset('/template/base/vendors.bundle.css') }}"> -->
        <!-- <link rel="stylesheet" href="{{asset('/template/default/base/style.bundle.css') }}"> -->
        <link rel="stylesheet" href="{{asset('/template/base/custom-vendors.bundle.css') }}">
        <link rel="stylesheet" href="{{asset('/template/default/base/custom-style.bundle.css') }}">

        @yield('css')
        


        <style type="text/css">
            .margin-bottom-10 {
                margin-bottom: 10px;
            }
            .text-indent-20 {
                text-indent: 20px;
            }
            .margin-left-10 {
                margin-left: 10px;
            }
            .eac-plate-dark a {
                color: #fff;
                text-decoration: none;
            }
        </style>

    </head>
    <body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
        @include('panels.navbar')
        <div class="app-body">
            @include('panels.sidebar')
            <main class="main">
                <!-- @include('panels.breadcrumb') -->

                @yield('breadcrumbs')
                <div class="container-fluid">
                    <div class="animated fadeIn">
                        @yield('content')
                    </div>
                </div>
                <!-- /.conainer-fluid -->
            </main>
            <!-- /.panels.asidemenu section -->
        </div>  
        @include('panels.footer')

        @include('panels.script')
    </body>
</html>
