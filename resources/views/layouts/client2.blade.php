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
        <title>FusedSoftware</title>
        
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

        <!-- Main styles for this application -->
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        @yield('css')

        <style type="text/css">
            body {
                background-color: #fff;
            }
            .margin-bottom-10 {
                margin-bottom: 10px;
            }
            .text-indent-20 {
                text-indent: 20px;
            }

            #site-container {
                background-color: #282828;
            }

            #topnav {
                position: absolute;
                width: 100%;
            }

            .main-menu-items {
                float: right;
            }

            .main-menu-items li {
                list-style: none;
                float: left;
            }

            .main-menu-items li a {
                text-transform: uppercase;
                color: #fff;                
            }

            #topnav-container {
                max-width: 1170px;
                width: 100%;
                margin: 1% auto;
            }

            .contents {
                width: 100%;
            }

            .hr-line {
                width: 100%;
                border-bottom: 1px solid #fff;
                height: 1px;
            }

            .width-40 {
                width: 40%;
            }

            .width-30 {
                width: 30%;
            }

            .margin-auto {
                margin: 0 auto;
            }

            .margin-top-20 {
                margin-top: 20px;
            }


            .margin-top-10 {
                margin-top: 10px;
            }

            .margin-top-15 {
                margin-top: 15px;
            }

            .margin-top-5 {
                margin-top: 5px;
            }

            .margin-bottom-15 {
                margin-bottom: 15px;
            }

            .font-white {
                color: #fff;
            }

            .newline {
                width: 100%;
                margin-top: 5px;
                margin-bottom: 5px;
            }

            #top-view {
                background-image: url(https://ultimateyoufuel.com/wp-content/uploads/2018/04/pexels-photo-407293.jpg);
                background-position: center center;
                background-size: cover;
                padding: 100px 0px 80px
            }
        </style>

    </head>
    <body class="app">
        <div id="topnav">
            <div id="topnav-container">
                <div class="row">
                <div class="col-md-6">
                    <!-- Brand/logo -->
                      <a class="navbar-brand" href="#">
                          <img src="{{asset('images/logo.png')}}" width="258" height="43">
                      </a>
                </div>
                <div class="col-md-6">
                    <ul class="main-menu-items pull-right">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Meals</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contact Us</a>
                        </li>
                    </ul>
                </div>
                </div>
            </div>  
        </div>
        <div id="site-container">
            <div class="contents" id="top-view">
                <div class="container">
                    <div class="row font-white">
                        <div class="col-md-12 text-center">
                            <div class="width-30 margin-auto">
                                <img src="{{asset('images/center-logo.png')}}" width="150">
                                <div class="hr-line margin-top-10 margin-bottom-15">&nbsp;</div>
                            </div>
                        </div>
                        <div class="col-md-12 text-center">
                            <h1 class="sow-headline" style="font-size: 45px;">"EATING HEALTHY MADE EASY AND DELICIOUS."</h1>
                            <div class="newline">&nbsp;</div>
                            <img src="{{asset('images/video-home.jpg')}}" width="55%">
                        </div>
                    </div>
                </div>
            </div>
            <div class="contents">
                <div class="container">
                   <div class="row">
                        <div class="col-md-12">
                            <pre>
                            Who said a healthy, lean meal can't be delicious?

                            We introduce you our healthylicious Paleo Pack Menus straight out the kitchen - containing organic food exuding the love, care and tenderness found in your momma's very best, heart-warming dishes.

                            Only this time, you won't be adding fatty digits to your body account.

                            That's right, we're going to take care of you.

                            Because we know how hard it is to maintain a healthy lifestyle while having an unlimited number of worries pulling you away form the goals you've been envisioning for so long.

                            And when it comes to prepping a neatly balanced, ab-builing and super tasty meal, that's just a whole lotta thinking and executing to do in the very short hours you have to organize your day.

                            Thus at Ultimateyou, we help you scratch your goals off the whiteboard by giving you affordable weekly meal packs to make up for the lack of availability you've got to prepare your own meals.
                            </pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('pages.client.footer')

        @include('pages.client.script')
    </body>
</html>

