@extends('layouts.client')


@section('page-title', '- Order')

@section('content')

<div id="checkout-container">
@include($view.'message-label')
@include($view.'shipping-form')
@include($view.'order-summary-container')
@include($view.'coupon')
@include($view.'card-listing')
@include($view.'card')
@include($view.'checkout-checkbox')
@include($view.'checkout-btn')
</div>
@endsection


@section('css')
<link href="{{ asset('css/client.css') }}" rel="stylesheet">

<style type="text/css">
#checkout-message {
    text-align: left !important;
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
        width: 700px;
    }
}

@media only screen and (min-width: 1024px) and (min-height: 768px){
    .container {
        width: 700px;
    }
}

</style>
@endsection

@section('script')

<script src="{{asset('vendors/js/jquery.maskedinput.min.js')}}"></script>
<script src="{{asset('vendors/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/validator.js')}}"></script>
<script src="{{asset('js/views/checkoutv2/checkout.js')}}"></script>
<script src="{{asset('js/views/checkoutv2/coupon.js')}}"></script>
<script src="{{asset('js/views/checkoutv2/cards.js')}}"></script>

<script type="text/javascript">
    var config = {
        responseCodes: @json($codes),
        delivery_timings_id: "<?=$delivery_timings_id ?? 0?>",
        delivery_zone_timings_id: "<?=$delivery_zone_timings_id ?? 0?>"
    }
</script>

<script type="text/javascript">

    var url = {
        deliveryTimeUrl: "{{url($deliveryTimeUrl)}}/",
        orderSummaryUrl: "{{url($orderSummaryUrl)}}",
        successUrl: "{{url($successUrl)}}",
        verifyCouponUrl: "{{url($verifyCouponUrl)}}",
        cardsUrl: "{{url($cardsUrl)}}",
        promoInputsUrl: "{{url($promoInputsUrl)}}",
        deletePromoUrl: "{{url($deletePromoUrl)}}",
        shipppingUrl: "{{url($shipppingUrl)}}/",
        removeOrderPlanUrl: "{{url($removeOrderPlanUrl)}}",
        checkoutUrl: "{{url($checkoutUrl)}}",
        getDeliveryTimingsCutoffDateTimeByDeliveryZoneTimingsUrl: "{{url($getDeliveryTimingsCutoffDateTimeByDeliveryZoneTimingsUrl)}}",
        getDeliveryTimingsCutoffDateTimeByTimingsUrl: "{{url($getDeliveryTimingsCutoffDateTimeByTimingsUrl)}}",
    }

    var Config = {
        delivery_zone_timings_id: "{{$delivery_zone_timings_id ?? 0}}",
        delivery_timings_id:  "{{$delivery_timings_id ?? 0}}"
    }

    var auth = "{{Auth::check()}}";
    var loginForm = '#login-form';
    var shippingForm = '#shipping-form';

    $(document).ready(function() {

        $("#card_expiration_date").mask("99/99");
        $("#card_cvc").mask("999");
        $("#card_number").mask("9999 9999 9999 9999");

        // Init checkout page
        Card.init({
            cardsUrl: url.cardsUrl,
        });

        // Init checkout page
        Checkout.init({
            checkoutUrl: url.checkoutUrl,
            successUrl: url.successUrl,
            removeOrderPlanUrl: url.removeOrderPlanUrl,
            cardObject: Card
        });

        Coupon.init( {
            verifyCouponUrl: url.verifyCouponUrl,
            promoInputsUrl: url.promoInputsUrl,
            deletePromoUrl: url.deletePromoUrl
        });

        // Load cards
        Card.loadCards();
        Checkout.checkoutCheckbox();

        $(document).on('change','select#delivery_zone_timings_id', function() {
            Checkout.checkoutCheckbox($(this).val());
        });

        $(document).on('change','select#delivery_zone_id', function() {

            let defaultDeliveryTimingsId = '';
            const interval = setInterval(function() {
                console.log($('select#delivery_zone_timings_id').val());

                if ($('select#delivery_zone_timings_id').val() != '') {
                    defaultDeliveryTimingsId = $('select#delivery_zone_timings_id').val();
                    clearInterval(interval);
                    Checkout.checkoutCheckbox($('select#delivery_zone_timings_id').val());
                }
            }, 1000);
            
        });

        $(document).on('click','.radio-cardlist', function() {
            Card.bindCreditCards();
        });


        $(document).on('bind change', '#delivery_zone_id', function() { 
            setDeliveryTimingDefault();
        });

        $('#promo-form').submit(function() { 
            Coupon.storeCouponCode();
            return false;
        });

        setDeliveryTimingDefault();
        orderSummary();
        Card.bindCreditCards();
    });

    function setDeliveryTimingDefault() {
        setDeliveryTimings($('#delivery_zone_id').find('option:selected').val());
    }

    function setDeliveryTimings(id) {
        if (id == '' || id == null || id == undefined) {
            return;
        }
        System.setAjaxRequest(
            url.deliveryTimeUrl+id,
            '',
            'GET',
            function(response) {
                var delivery_zone_timings_id = $('#delivery_zone_timings_id');
                var options = '<option value="">Choose Delivery Time</option>';

                var selected = '';
                for (var i in response) {
                    selected = '';
                    if (Config.delivery_zone_timings_id == response[i].id) {
                        selected = 'selected';
                    }
                    options += "<option "+selected+" "+(response.length <= 1 ? 'selected' : '')+" value='"+response[i].id+"'>"+response[i].date+"</option>";
                }

                delivery_zone_timings_id.html(options);
            }
        );
    }    

    function orderSummary() {
        System.blockUI('#order-summary-wrapper');
        System.lazyLoading( function() {
            System.setAjaxRequest(
                url.orderSummaryUrl,
                '',
                'GET',
                function(response) {
                    System.unblockUI();
                    if (response != '') {
                        $('#order-summary-wrapper').html(response);
                    }
                },
                function() {
                    System.unblockUI();
                },
                'html'
            );
        });
    }

</script>
@endsection

@section('content')

@endsection