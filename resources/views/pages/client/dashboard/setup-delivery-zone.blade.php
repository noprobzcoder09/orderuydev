@extends('layouts.client-no-nav')

@section('title')
Setup Delivery Zone
@endsection
@section('content')
<div id="page-container">
    <h1>Setup Delivery Zone</h1>
    <p>You're delivery area/time is no longer available. Please select a new one.</p>
    <div class="row">
        <div class="col-md-12">
        @include('errors.messages')
        </div>
    </div>
    <div id="page-wrapper">
        @include($view.'setup-delivery-zone.form')
    </div>
</div>
@endsection

@section('css')
<link href="{{ asset('css/client_dashboard.css') }}" rel="stylesheet">

<style type="text/css">
    #page-container {
        width: 50%;
        margin: 0 auto;
    }
</style>
@endsection

@section('script')
<script src="{{asset('vendors/js/jquery.maskedinput.min.js')}}"></script>
<script src="{{asset('vendors/js/jquery.validate.min.js')}}"></script>
<script type="text/javascript">

    var url = {
        deliveryTimeUrl: "{{url($deliveryTimeUrl)}}/",
        updateDeliveryZoneUrl: "{{url($updateDeliveryZoneUrl)}}"
    };

    var Element = {
        container: '#page-container > #page-wrapper',
        cardModal: '#creditcard-modal',
        cardModalContainer: '#creditcard-modal > .modal-dialog',
        cardMessage: '#card-message',
        hideCardModal: function() {
            $(Element.cardModal).modal('hide');
        },
        cardInputFormatter: function() {
            $("#card_expiration_date").mask("99/99");
            $("#card_cvc").mask("999");
            $("#card_number").mask("9999 9999 9999 9999");
        }
    }

    $(document).ready( function() {
        Validator.validate();
        setDeliveryTimings(Form.getDeliveryZoneId());
        $(document).on('bind change', '#delivery_zone_id', function() { 
            setDeliveryTimings(Form.getDeliveryZoneId());
        });
    });

    function setDeliveryTimings(id) {
        System.setAjaxRequest(
            url.deliveryTimeUrl+id,
            '',
            'GET',
            function(response) {
                var delivery_timings_id = $('#delivery_zone_timings_id');
                var options = '<option value="">Choose Delivery Time</option>';
                for (var i in response) {
                    options += "<option "+(response.length <= 1 ? 'selected' : '')+" value='"+response[i].id+"'>"+response[i].date+"</option>";
                }

                delivery_timings_id.html(options);
            }
        );
    } 
    
    var Action = function() {

        function updateDeliveryZone() {
            try
            {
                Validator.isValid();
                System.blockUI($(Form.id));
                System.lazyLoading( function() {
                    Request.updateDeliveryZone(Form.getDeliveryZoneTimingId());
                }); 
            }
            catch(error) {
                console.log(error);
            }
        }

        return {
            updateDeliveryZone: updateDeliveryZone
        }
    }();

    var Form = {
        id: '#delivery-form' ,
        getDeliveryZoneTimingId: function() {
            return $('#delivery_zone_timings_id').find('option:selected').val();
        },
        getDeliveryZoneId: function() {
            return $('#delivery_zone_id').find('option:selected').val();
        }
    }

    var Validator = {
        validate: function() {
            $validator = $(Form.id).validate({
                rules: {
                    delivery_zone_id: {
                        required: true
                    },
                    delivery_zone_timings_id: {
                        required: true
                    }
                 },   
                errorPlacement : function(error, element) {
                    var placement = $(element).data('error');
                    error.addClass( 'invalid-feedback' );
                    if(element.attr('name') == 'agree') {
                        element.parent().find('label').attr('style','color: red !important');
                    } else {
                        element.attr('style','border: 1px solid red !important;');
                    }
                },
                unhighlight: function (element, errorClass, validClass) {
                    var element = $(element);
                    if(element.attr('name') == 'agree') {
                        element.parent().find('label').removeAttr('style');
                    } else {
                        $( element ).removeAttr('style');
                    }
                },
                highlight: function (element, errorClass, validClass) {
                    var element = $(element);
                    if(element.attr('name') == 'agree') {
                        element.parent().find('label').attr('style','color: red !important');
                    } else {
                        element.attr('style','border: 1px solid red !important;');
                    }
                }
            });
        },
        isValid: function() {
            var valid = $(Form.id).valid();
            if(!valid) {
                $validator.focusInvalid();
                throw 'Invalid form!'
            }
            return true;
        }
    }

    var Request = {
        updateDeliveryZone: function(deliveryZoneTimingId) {
            System.setAjaxRequest(
                url.updateDeliveryZoneUrl,
                {delivery_zone_timings_id: deliveryZoneTimingId},
                'PATCH',
                function(response) {
                    System.unblockUI();
                    if (response.success == true) {
                        Alert.success('Updated',response.message); 
                        window.location.reload();
                    } else {
                        Alert.error('Failed',response.message);    
                    }
                },
                function(error) {
                    System.unblockUI();
                    Alert.error('Error',System.errorTextMessage);
                },
                'JSON',
                false
            );
        }
    };

</script>

@endsection