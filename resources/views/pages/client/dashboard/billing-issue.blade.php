@extends('layouts.client-no-nav')

@section('page-title', '- Billing Issue')
@section('content')
<div id="page-container">
    <h1>Billing Issue</h1>
    <div class="row">
        <div class="col-md-12">
        @include('errors.messages')
        </div>
    </div>
    <div id="page-wrapper">
    </div>
</div>
@include($view.'card-modal')
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
        cancelSubscriptionCycleUrl: "{{url($cancelSubscriptionCycleUrl)}}",
        cancelSubscriptionUrl: "{{url($cancelSubscriptionUrl)}}",
        viewSubscriptionsUrl: "{{url($viewSubscriptionsUrl)}}",
        dashboardUrl: "{{url($dashboardUrl)}}",
        chargeCardUrl: "{{url($chargeCardUrl)}}",
        updateCardAndBillUrl: "{{url($updateCardAndBillUrl)}}"
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
        Action.viewSubscriptions();
        Element.cardInputFormatter();
        Form.cardValidation();
    });
    
    var Action = function() {
        function cancelSubscriptionCycle(_this, subscriptionId, subscriptionCycleId) {
            Alert.confirm(
                'Cancel','Do you also wish to cancel this subscription?',
                'No, Just Cancel the last week.',
                'Yes, Cancel Subscription.',
                function(instance, toast) {
                    System.blockUI($(_this).closest('tr'));
                    System.lazyLoading( function() {
                        Request.cancelSubscriptionCycle(
                            subscriptionId,
                            subscriptionCycleId
                        );
                    }); 
                },
                function(instance, toast) {
                    System.blockUI($(_this).closest('tr'));
                    System.lazyLoading( function() {
                        Request.cancelSubscription(
                            subscriptionId, subscriptionCycleId
                        );
                    });
                }
            ); 
        }

        function viewSubscriptions() {
            System.blockUI($(Element.container));
            System.lazyLoading( function() {
                Request.viewSubscriptions();
            }); 
        }

        function chargeCard() {
            Alert.confirm(
                'Charge','Are you sure you want to charge your card?',
                'Yes',
                'No',
                function(instance, toast) {
                    System.blockUI($(Element.container));
                    System.lazyLoading( function() {
                        Request.chargeCard();
                    }); 
                },
                function(instance, toast) {
                    
                }
            ); 
        }

        function updateAndBillCard() {
            Form.validateCard();
            System.blockUI($(Element.cardModalContainer));
            System.lazyLoading( function() {
                Request.updateAndBillCard();
            }); 
        }

        return {
            cancelSubscriptionCycle: cancelSubscriptionCycle,
            viewSubscriptions: viewSubscriptions,
            chargeCard: chargeCard,
            updateAndBillCard
        }
    }();

    var Form = {
        cardFormId: '#credit-card-form',
        cardValidation: function() {
            $validator = $(Form.cardFormId).validate({
                rules: {
                    card_name: {
                        required: true
                    },
                    card_number: {
                        required: true
                    },
                    card_expiration_date: {
                        required: true
                    },
                    card_cvc: {
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
        validateCard: function() {
            var valid = $(Form.cardFormId).valid();
            if(!valid) {
                $validator.focusInvalid();
                throw 'Invalid Credit Card form'
            }
            return true;
        }
    }

    var Request = {
        cancelSubscriptionCycle: function (subscriptionId, subscriptionCycleId) {
           System.setAjaxRequest(
                url.cancelSubscriptionCycleUrl+'/'+subscriptionId+'/'+subscriptionCycleId,
                '',
                'PATCH',
                function(response) {
                    System.unblockUI();
                    if (response.success == true) {
                        Alert.success('Cancelled',response.message); 
                        Request.viewSubscriptions();
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
        },
        cancelSubscription: function (subscriptionId, subscriptionCycleId) {
           System.setAjaxRequest(
                url.cancelSubscriptionUrl+'/'+subscriptionId+'/'+subscriptionCycleId,
                '',
                'PATCH',
                function(response) {
                    System.unblockUI();
                    if (response.success == true) {  
                        Alert.success('Cancelled',response.message);  
                        Request.viewSubscriptions();
                    } else {
                        System.errorMessage(response.message);
                        Alert.error('Failed',response.message);    
                    }
                },
                function(error) {
                    System.unblockUI();
                    System.errorMessage(response.message);
                    Alert.error('Error',System.errorTextMessage);
                },
                'JSON',
                false
            );
        },
        viewSubscriptions: function() {
            System.setAjaxRequest(
                url.viewSubscriptionsUrl,
                '',
                'GET',
                function(response) {
                    System.unblockUI();
                    $(Element.container).html(response.contents);

                    if (response.nounpaidsubscriptions == true) {
                        System.lazyLoading( function() {
                            window.location.href = url.dashboardUrl;
                        });
                    }
                },
                function(error) {
                    System.unblockUI();
                    Alert.error('Error',System.errorTextMessage);
                },
                'JSON',
                true
            );
        },
        chargeCard: function() {
            System.setAjaxRequest(
                url.chargeCardUrl,
                '',
                'PATCH',
                function(response) {
                    System.unblockUI();
                    if (response.success == true) {
                        System.successMessage(response.message);
                        Alert.success('Success',response.message);
                        Request.viewSubscriptions();
                    } else {
                        System.errorMessage(response.message);
                        Alert.error('Error',response.message);
                    }
                },
                function(error) {
                    System.unblockUI();
                    System.errorMessage(error.responseJSON.message);
                    Alert.error('Error', error.responseJSON.message);
                },
                'JSON',
                true
            );
        },
        updateAndBillCard: function() {
            System.setAjaxRequest(
                url.updateCardAndBillUrl,
                $(Form.cardFormId).serialize(),
                'PATCH',
                function(response) {
                    System.unblockUI();
                    if (response.success == true) {
                        Element.hideCardModal();
                        System.successMessage(response.message);
                        Alert.success('Success',response.message);
                        Request.viewSubscriptions();
                    } else {
                        System.errorMessage(response.message, Element.cardMessage);
                        Alert.error('Error',response.message);
                    }
                },
                function(error) {
                    System.unblockUI();
                    System.errorMessage(error.responseJSON.message, Element.cardMessage);
                    Alert.error('Error', error.responseJSON.message);
                },
                'JSON',
                true
            );
        }
        
    };

</script>

@endsection