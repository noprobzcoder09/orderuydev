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
    <title>FusedSoftware</title>

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
</style>

</head>
<body class="app flex-row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card-group">
                    <div class="card p-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                @include('errors.messages')
                                </div>
                            </div>
                            <form id="changepassword-form">
                                <h1>Change Password</h1>
                                <p>&nbsp;</p>
                                @csrf
                                <input type="hidden" name="email_token" value="{{isset($email_token) ? $email_token : ''}}">
                                <div class="input-group mb-4">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-lock"></i></span>
                                    </div>
                                    <input type="password" name="password" id="password" required class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('Password') }}">
                                </div>
                                <div class="input-group mb-4">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-lock"></i></span>
                                    </div>
                                    <input type="password" name="repeat_password" id="repeat_password" required class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('Repeat Password') }}">
                                </div>
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-primary px-4"><i class="fa"></i>&nbsp;{{ __('Submit') }}</button>
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
        
        var form = '#changepassword-form';
         Validator.init(form, {
                rules: {
                    password: 'required',
                    repeat_password: {
                        equalTo: '#password'
                    }
                },
                messages: {
                    password: {
                        required: 'Please enter a password.',
                    },
                    repeat_password: {
                        equalTo: 'Please did not matched.',
                    },
                },
                submitHandler: function () {
                    System.addSpinner($(form).find("button[type='submit'] > i"));
                    System.setAjaxRequest(
                        "{{url('/verify/email')}}",
                        $(form).serialize(),
                        'POST',
                        function(response) {
                            if (response == 1) {
                                System.successMessage('Successfully Verified user!');
                                System.lazyLoading( function() {
                                    window.location.href = "{{url('/')}}";
                                }, 2000);
                            }
                            else {
                                System.errorMessage('User is already verified!');
                            }
                            System.removeSpinner($(form).find("button[type='submit'] > i"),'');
                        },
                        function(errors) {
                            var messages = '';
                            for(var i in errors.responseJSON.errors) {
                                messages += '<li>'+errors.responseJSON.errors[i]+'</li>';
                            }
                            System.errorMessage('<ul>'+messages+'</ul>');
                            System.removeSpinner($(form).find("button[type='submit'] > i"),'');
                        }
                    );
                    return false;
                }
            });

    </script>
</body>
</html>
