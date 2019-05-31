@extends('layouts.client')

@section('page-title')
Order
@endsection

@section('content')
@include($view.'header')
@include($view.'message-label')
@include($view.'email-login-form')
@include($view.'login-form')
@include($view.'register-form')
@endsection

@section('css')
<link href="{{ asset('css/client.css') }}" rel="stylesheet">

<style type="text/css">
#checkout-message {
    text-align: left !important;
}

.btn-ecommerce-login {
    font-size: 20px;
    font-weight: bold;
    background-color: #ccc !important;
    color: #fff;
    padding-right: 30px;
    padding-left: 30px;
    text-decoration: none;
    text-transform: uppercase;
    border-radius: 60px;
    -webkit-border-radius: 60px;
    -moz-border-radius: 60px;
}

#container-devider {
    border-right: 1px solid #ccc;
}

.btn-gray {
    background-color: #ccc !important;
}

.register-login-form {
    margin-bottom: 20px;
}

@media only screen and (min-width: 200px) and (max-width: 384px){ 
    
}

@media only screen and (min-width: 385px) and (max-width: 479px){ 
    
}

@media only screen and (min-width: 480px) and (max-width: 767px){ 
  
}

@media only screen and (min-width: 768px) and (max-width: 991px){
    
}

@media only screen and (min-width: 992px) and (max-width: 1999px){
    .container {
        width: 400px;
    }
}

@media only screen and (min-width: 1024px) and (min-height: 768px){
    .container {
        width: 400px;
    }
}

</style>
@endsection

@section('script')

<script src="{{asset('vendors/js/jquery.maskedinput.min.js')}}"></script>
<script src="{{asset('vendors/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/validator.js')}}"></script>
<script src="{{asset('js/views/checkoutv2/login.js')}}"></script>
<script src="{{asset('js/views/checkoutv2/email-login.js')}}"></script>
<script src="{{asset('js/views/checkoutv2/checkout.js')}}"></script>
<script src="{{asset('js/views/checkoutv2/register.js')}}"></script>

<script type="text/javascript">
    var config = {
        responseCodes: @json($codes)
    };
</script>

<script type="text/javascript">

    var url = {
        verifyEmailLoginUrl: "{{url($verifyEmailLoginUrl)}}",
        loginUrl: "{{url($loginUrl)}}",
        registerSessionUrl: "{{url($registerSessionUrl)}}"
    }

    var auth = "{{Auth::check()}}";
    var ajaxResponse = false, passwordVisible = false;
    var loginForm = '#login-form';
    var emailLoginForm = '#email-login-form';
    var registrationForm = '#register-form';

    $(document).ready(function() {
        // Init Login Form
        Login.init({
            loginUrl: url.loginUrl,
            logoutUrl: url.logoutUrl,
            accountUrl: url.accountUrl,
            verifyEmailLoginUrl: url.verifyEmailLoginUrl,
            shipppingUrl: url.shipppingUrl
        });

        EmailLogin.init({
            verifyEmailLoginUrl: url.verifyEmailLoginUrl,
        });

        Register.init({
            registerSessionUrl: url.registerSessionUrl,
        });

        $(loginForm).submit( function(e) {
            Login.verify();
            return false;
        });

        $(emailLoginForm).submit( function(e) {
            EmailLogin.verify();
            return false;
        });

        $(registrationForm).submit( function(e) {
            Register.saveRegistration();
            return false;
        });
    });


</script>
@endsection

@section('content')

@endsection