@extends('layouts.client')

@section('content')
<div id="rootwizard">
    <div class="navbar">
        <div class="navbar-inner">
            <div class="container text-center">
                <div id="wizzard-wrapper">
                    <ul class="nav nav-pills">
                        <li class="nav-link"><a href="#tab1" data-toggle="tab">Your Plan</a></li>
                        <li class="nav-link"><a href="#tab2" data-toggle="tab">Log In / Register</a></li>
                        <li class="nav-link"><a href="#tab3" data-toggle="tab">Checkout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div id="bar" class="progress">
        <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
    </div>
    <div class="tab-content">
        <div class="tab-pane" id="tab1">
            @include($view.'selection')
        </div>
        <div class="tab-pane" id="tab2">
            @include($view.'login')
        </div>
        <div class="tab-pane" id="tab3">
            @include($view.'shipping')
            <div id="order-summary-wrapper"></div>
            @include($view.'promo')
            <div id="customer-card-wrapper" class="text-center margin-top-20">@include($view.'cards')</div>
            <div id="customer-card-form">@include($view.'checkout')</div>
            <div class="row margin-top-20 text-center">
                <div class="col-md-12">
                    <a href="javascript:;" class="btn btn-lg btn-ecommerce btn-checkout next">Place Order</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<link href="{{ asset('css/client.css') }}" rel="stylesheet">

<style type="text/css">
#checkout-message {
    text-align: left !important;
}

</style>
@endsection

@section('script')

<script src="{{asset('vendors/js/jquery.maskedinput.min.js')}}"></script>
<script src="{{asset('vendors/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/validator.js')}}"></script>
<script src="{{asset('js/views/checkout/menu.js')}}"></script>
<script src="{{asset('js/views/checkout/login.js')}}"></script>
<script src="{{asset('js/views/checkout/checkout.js')}}"></script>
<script src="{{asset('js/views/checkout/coupons.js')}}"></script>
<script src="{{asset('js/views/checkout/cards.js')}}"></script>

<script type="text/javascript">
    var config = {
        responseCodes: @json($codes)
    };
</script>

<script type="text/javascript">

    var url = {
        deliveryTimeUrl: "{{url($deliveryTimeUrl)}}/",
        addtoCartUrl: "{{url($addtoCartUrl)}}",
        verifyEmailUrl: "{{url($verifyEmailUrl)}}",
        loginUrl: "{{url($loginUrl)}}",
        accountUrl: "{{url($accountUrl)}}",
        logoutUrl: "{{url($logoutUrl)}}",
        orderSummaryUrl: "{{url($orderSummaryUrl)}}",
        checkoutUrl: "{{url($checkoutUrl)}}",
        successUrl: "{{url($successUrl)}}",
        verifyCouponUrl: "{{url($verifyCouponUrl)}}?meal_plans_id={{$id}}",
        cardsUrl: "{{url($cardsUrl)}}",
        promoInputsUrl: "{{url($promoInputsUrl)}}",
        deletePromoUrl: "{{url($deletePromoUrl)}}",
        shipppingUrl: "{{url($shipppingUrl)}}/",
        removeOrderPlanUrl: "{{url($removeOrderPlanUrl)}}",
        signupUrl: "{{url($signupUrl)}}"
    }

    var auth = "{{Auth::check()}}";
    var ajaxResponse = false, passwordVisible = false;
    var loginForm = '#login-form';
    var wizzardElement = $('#wizzard-wrapper');

    $(document).ready(function() {

        $("#card_expiration_date").mask("99/99");
        $("#card_cvc").mask("999");
        $("#card_number").mask("9999 9999 9999 9999");

        // Activate selections
        Menu.init({
            addToCartUrl: url.addtoCartUrl,
            orderSummaryUrl: url.orderSummaryUrl,
            noMeals: "{{$meals['noMeals']}}"
        });

        // Init Login Form
        Login.init({
            loginUrl: url.loginUrl,
            logoutUrl: url.logoutUrl,
            accountUrl: url.accountUrl,
            verifyEmailUrl: url.verifyEmailUrl,
            shipppingUrl: url.shipppingUrl
        });

        Login.setAuth(auth);

        if (Login.auth()) {
            Login.getShipping();
        }

        // Init checkout page
        Cards.init({
            cardsUrl: url.cardsUrl,
        });

        // Init checkout page
        Checkout.init({
            checkoutUrl: url.checkoutUrl,
            successUrl: url.successUrl,
            removeOrderPlanUrl: url.removeOrderPlanUrl
        });

        Coupons.init( {
            verifyCouponUrl: url.verifyCouponUrl,
            promoInputsUrl: url.promoInputsUrl,
            deletePromoUrl: url.deletePromoUrl
        });

        Menu.orderSummary();
        // Coupons.promoInputs();

        // Load cards
        Cards.loadCards();

        $(document).on('click','.radio-cardlist', function() {
            Cards.bindCreditCards();
        });

        var object;

        $('#rootwizard').bootstrapWizard({
            'nextSelector': '.btn-continue', 'previousSelector': '.button-previous',
            'lastSelector': '.btn-checkout',
            onTabClick: function(tab, navigation, index) {
                if (index == 2) {

        /*if (Login.passwordVisible()) {
        if (!Login.auth()) {
        return false;
        }
        } else {
        if (!Login.isValid()) {
        return false;
        }
        }*/
        }
        return true;
        },
        onLast: function(tab, navigation, index) {
            var s = Checkout.isValid();
            var c = Cards.isValidCRD();
            if (!c || !s) {
                if (!c) {
                    Alert.error('Invalid!','Credit Card form must be completed.');
                }
                return false;
            }

            Checkout.process();

            return Checkout.response;
        },
        onNext: function(tab, navigation, index) {
            if(index == 1) {
                if (!Menu.isValid()) {
                    return false;
                }
                object = Menu;
                Menu.addtocart("{{$id}}");

                if (Login.auth()) {
                    wizzardElement
                    .find('ul > li')
                    .eq(2)
                    .find('a')
                    .trigger('click');
                }

                if (object.response()) {
                    setTabFocus('login', 'email');
                }
            }

            else if(index == 2) {
                
                object = Login;

                if (Login.auth()) {
                    return true;
                }

                Login.checkEmail();

                if (Login.isLogin()) {
                    Login.login();
                } 
                else {
                    Login.signup();
                }

                if (object.response()) {
                    setTabFocus('shipping', 'first_name');
                }

            }

            return object.response();

        }, onTabShow: function(tab, navigation, index) {
            var $total = navigation.find('li').length;
            var $current = index+1;
            var $percent = ($current/$total) * 100;
            $('#rootwizard .progress-bar').css({width:$percent+'%'});
        }});

        $(document).on('bind change', '#delivery_zone_id', function() {	
            setDeliveryTimings($(this).find('option:selected').val());
        });

        $(document).on('click', '.meal-select', function() {	

        });
    });

    function setDeliveryTimings(id)
    {
        System.setAjaxRequest(
            url.deliveryTimeUrl+id,
            '',
            'GET',
            function(response) {
                var delivery_zone_timings_id = $('#delivery_zone_timings_id');
                var options = '<option value="">Choose Delivery Time</option>';

                for (var i in response) {
                    options += "<option "+(response.length <= 1 ? 'selected' : '')+" value='"+response[i].id+"'>"+response[i].date+"</option>";
                }

                delivery_zone_timings_id.html(options);
            }
            );
    }    

    function setTabFocus(tab, field)
    {
        var tab1 = 'plan';
        var tab2 = 'login';
        var tab3 = 'shipping';
        var eq = 0;
        var container = $('.tab-content');
        if (tab == tab1) {
            tab = '#tab1';
            eq = 0;
        }

        if (tab == tab2) {
            tab = '#tab2';
            eq = 1;
        }

        if (tab == tab3) {
            tab = '#tab3';
            eq = 2;
        }
        container
            .find('.tab-pane').eq(eq)
            .find('form input[name="'+field+'"]')
            .focus();
    }

</script>
@endsection

@section('content')

@endsection