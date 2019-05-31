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
    <link rel="shortcut icon" href="img/favicon.png">
    <title>Ultimate You Fuel - Login</title>

    <!-- Icons -->
    <link href="{{ asset('vendors/css/flag-icon.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/css/simple-line-icons.min.css') }}" rel="stylesheet">

    <!-- Main styles for this application -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- Custom styles for this application -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

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

    body {
        background-image: url('images/background/bg.jpg');
        background-size: cover;
    }

    #login-wrapper {
        border: 0 none !important;
        background: none !important;
    }

    .card-body {
        background:rgba(255, 255, 255,0.6);
        padding: 2rem !important;
        border-radius: 0 0 15px 15px;
    }

    .card-header-youfuel {
        background: rgba(0, 0, 0,0.6);
        border-radius: 15px 15px 0 0;
        padding: 1.3rem !important;
        text-align: center;
    }

    label {
        margin-bottom: 0 !important;
        font-weight: bold;
    }

    form input[type="password"],
    form input[type="email"] {
        border-radius: 5px;
        background: url("{{asset('images/background/repeater.jpg')}}") repeat;
        opacity: 1 !important;
    }

    form input[type="password"]:focus,
    form input[type="email"]:focus {
         border-color: none !important;
        -webkit-box-shadow: none !important;
        box-shadow: none !important;
        background-color: transparent !important;
    }

    a {
        color: #79bf53;
    }

    p {
        padding: 0;
        margin: 0;
    }

    .btn-bg {
         background-color: #79bf53 !important;
        text-transform: uppercase;
         border: 0 none !important;
        border-radius: 5px;
    }

    #btnforgotpassword {
        color: #000 !important;
    }

    .form-btn {
        width: 70%;
        background-color: #79bf53 !important;
        text-transform: uppercase;
         border: 0 none !important;
        border-radius: 5px;
    }
</style>

</head>
<body class="app flex-row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card-group">
                    <div class="card" id="login-wrapper">
                        <div class="card-header-youfuel">
                            <img src="{{asset('images/logo.png')}}">
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                @include('errors.messages')
                                </div>
                            </div>
                            <form id="login-form">
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="password" required class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}">
                                </div>
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <button id="btnforgotpassword" onclick="forgotpassword()" type="button" class="btn btn-link px-0">{{ __('Forgot Your Password?') }}</button>
                                    </div>
                                    <div class="col-12 text-center">
                                        <button id="loginbutton" type="submit" class="btn form-btn btn-primary px-4"><i class="fa"></i>{{ __('Login Now') }}</button>
                                    </div>
                                    <div class="col-12 text-center margin-top-20">
                                        <p>{{ __('Not a customer?') }}</p>
                                        <p><a href="https://ultimateyoufuel.com/">{{__('Click here ')}}</a>{{__('To View Our Meal Plans') }}</p>
                                    </div>
                                </div>
                            </form>

                            <form id="forgotpassword-form" style="display: none;">
                                <label>Enter Email Address</label>
                                <div class="input-group mb-3">
                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus placeholder="{{ __('Email') }}">
                                </div>
                                <div class="row">
                                    <div class="col-12 text-left">
                                        <button type="button" onclick="login()" class="btn col-sm-3 btn-bg btn-primary px-4"><i class="fa"></i>{{ __('Back') }}</button>
                                        <button type="submit" class="btn  col-sm-8 btn-bg btn-primary px-4"><i class="fa"></i>{{ __('Reset Password') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap and necessary plugins -->
    <script src="{{asset('vendors/js/jquery.min.js')}}"></script>
    <script src="{{asset('vendors/js/popper.min.js')}}"></script>
    <script src="{{asset('vendors/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('vendors/js/pace.min.js')}}"></script> 
    <script src="{{asset('vendors/js/blockui.js')}}"></script> 
    <script src="{{asset('vendors/js/jquery.validate.min.js')}}"></script>
    

    <!-- CoreUI Pro main scripts -->
    <script src="{{asset('js/global.js')}}"></script>   
    <script src="{{asset('js/system.js')}}"></script>   
    <script src="{{asset('js/alert.js')}}"></script>    
    <script src="{{asset('js/validator.js')}}"></script> 

    <script type="text/javascript">

        if (window.history && window.history.pushState) {

            window.history.pushState('login', null, './login');

            $(window).on('popstate', function() {
                window.location = "{{url('login')}}";
            });

          }
        
        var loginForm = '#login-form';
        Validator.init(loginForm, {
            rules: {
                password: 'required',
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                password: {
                    required: 'Please enter a password.',
                },
                email: {
                    required: 'Please enter email address.',
                },
            },
            submitHandler: function () {
                System.addSpinner($(loginForm).find("button[type='submit'] > i"));
                System.lazyLoading( function() {
                    System.setAjaxRequest(
                        "{{url('/auth/login')}}",
                        $(loginForm).serialize(),
                        'POST',
                        function(response) {
                            if (response.success == 1) {
                                System.lazyLoading( function() {
                                    window.location.href=response.redirectPath;
                                });
                            } else {
                                System.errorMessage(response.message);
                            }
                            System.removeSpinner($(loginForm).find("button[type='submit'] > i"),'');
                        },
                        function(errors) {
                            var messages = '';
                            for(var i in errors.responseJSON.errors) {
                                messages += '<li>'+errors.responseJSON.errors[i]+'</li>';
                            }
                            System.errorMessage('<ul>'+messages+'</ul>');
                            System.removeSpinner($(loginForm).find("button[type='submit'] > i"),'');
                        }
                    );
                });
                return false;
            }
        });
        var forgotPasswordForm = '#forgotpassword-form';
        Validator.init(forgotPasswordForm, {
            rules: {
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                email: {
                    required: 'Please enter email addess..',
                },
            },
            submitHandler: function () {
                System.addSpinner($(forgotPasswordForm).find("button[type='submit'] > i"));
                System.setAjaxRequest(
                    "{{url('/auth/sendresetpassword')}}",
                    $(forgotPasswordForm).serialize(),
                    'POST',
                    function(response) {
                        if (response.success == 1) {
                           System.successMessage(response.message);
                           login();
                        } else {
                            System.errorMessage(response.message);
                        }
                        System.removeSpinner($(forgotPasswordForm).find("button[type='submit'] > i"),'');
                    },
                    function(errors) {
                        var messages = '';
                        for(var i in errors.responseJSON.errors) {
                            messages += '<li>'+errors.responseJSON.errors[i]+'</li>';
                        }
                        System.errorMessage('<ul>'+messages+'</ul>');
                        System.removeSpinner($(forgotPasswordForm).find("button[type='submit'] > i"),'');
                    }
                );
                return false;
            }
        });


        function forgotpassword() {
            $('#forgotpassword-form').fadeIn();
            $('#login-form').fadeOut();
        }

        function login() {
            $('#forgotpassword-form').fadeOut();
            $('#login-form').fadeIn();
        }
    </script>
</body>
</html>
