@extends('layouts.client')

@section('page-title', '- Dashboard')

@section('content')
<div id="rootwizard">
	<div class="navbar">
	    <div class="navbar-inner">
            <!-- <div class="container text-center"> -->
	        <div class="text-center">
	            <div id="wizzard-wrapper">
	            	<ul class="nav nav-pills">
	                    <li class="nav-link"><a href="#tab1" class="cursor-pointer" data-toggle="tab">Your Menu</a></li>
	                    <li class="nav-link"><a href="#tab2" class="cursor-pointer" data-toggle="tab">Manage Plans</a></li>
	                    <li class="nav-link"><a href="#tab3" class="cursor-pointer" data-toggle="tab">Billing</a></li>
	                    <li class="nav-link"><a href="#tab4" class="cursor-pointer" data-toggle="tab">Delivery</a></li>
                        <li class="nav-link"><a href="#tab5" class="cursor-pointer" data-toggle="tab">Profile</a></li>
	                </ul>
	            </div>
	        </div>
            <!-- </div> -->
	    </div>
	</div>
	<div id="bar" class="progress">
	    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
	</div>
	<div class="tab-content">
	    <div class="tab-pane" id="tab1" tab-title="FusedSoftware | Dashboard">
            @if(Request::has('ref') && strtolower(Request::get('ref')) == 'subscribed')
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="alert alert-success">
                        <h4>{{__('config.thank-you')}}</h4>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                setTimeout( function() {
                    // window.location.href = "{{url('dashboard')}}";
                },3000);
            </script>
            @endif    
            <div id="tab1-container">
                <div id="container-selections">
                    <p class="text-center">{{__('config.manage-plans-no-data')}}</p>
                </div>
                @include($view.'selections.save-selections-btn')
            </div>
        </div>
        <div class="tab-pane" id="tab2" tab-title="FusedSoftware | Manage Plans">
            @include($view.'manage-plans.title')
            @include($view.'manage-plans.main-buttons')
            <div id="listing-wrapper">
                <div class="listing">
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="text-center">Loading records.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row margin-top-20">
                <div class="col-md-12 text-center">
                @include($view.'manage-plans.new-plan-btn')
                </div>
            </div>

            <div class="margin-top-10">
                <div class="ql-editor">
                    @php echo !empty($manageSubscriptionText) ? $manageSubscriptionText : '' @endphp
                </div>
            </div>

            <!-- past subscription lists -->           
            @include($view.'customer.table-past-subscriptions')            
            <!-- /past subscription lists -->

        </div>
        <div class="tab-pane" id="tab3" tab-title="FusedSoftware | Billing">
            <div class="width-50">
                @include($view.'billing.info-address')
                @include($view.'billing.cards')
                @include($view.'billing.card-form')
            </div>
            
             <!-- invoices history -->           
             @include($view.'invoice.listings')            
            <!-- /invoices history -->

        </div>
        <div class="tab-pane" id="tab4" tab-title="FusedSoftware | Delivery">
            @include($view.'delivery.form')
        </div>
        <div class="tab-pane" id="tab5" tab-title="FusedSoftware | Profile">
            <div class="width-50">
            @include($view.'profile.form')
            </div>
        </div>
	</div>
</div>
@include($view.'manage-plans.new-plan-modal')
@include($view.'manage-plans.datepicker')
@endsection

@section('css')

<!-- quill editor -->
<link href="https://cdn.quilljs.com/1.1.6/quill.snow.css" rel="stylesheet">	

<link rel="stylesheet" href="{{ asset('template/custom/datatables/datatables.bundle.css') }}">
<link rel="stylesheet" href="{{ asset('template/default/base/custom-style.bundle.css') }}">
<link rel="stylesheet" href="{{ asset('template/base/custom-vendors.bundle.css') }}">
<link href="{{ asset('css/client_dashboard.css') }}" rel="stylesheet">
<link href="{{asset('vendors/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
<style type="text/css">
	#checkout-message {
		text-align: left !important;
	}

    .listing {
        background-color: #f0f3f5 !important;
        padding: 20px;
        margin-top: 40px;
    }

    .listing-btn {
        color: #000;
        text-decoration: underline;
        margin-right: 10px;
    }

    .listing .title {
        color: #95c11f;
        text-transform: uppercase;
    }

    .listing .listing-btn:hover {
        color: #000;
    }

    .sub-title {
        display: block;;
    }

    .listing .price {
        font-weight: bold;
        display: block;
    }

    .listing .qty {
        font-weight: bold;
        display: block;
    }

    #date-container {
        display: inline-block;
    }

    .select-style select {
        background-size: 10px 15px !important;
    }

    #table-order-summary tbody tr td {
        padding: 5px !important;
    }

    .delivery-timing-container {
        /*display: none;*/
    }

    .previous-selections-content {
        padding-top: 20px;
    }
    .btn {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
    }

    #table-invoice-wrapper,
    #table-past-subs-wrapper {
        padding-top: 40px;
    }

    #table-past-subs-wrapper tbody tr .mr-5{
        margin-right: 0 !important
    }

    #table-past-subs-wrapper tbody tr.shown td.past-selections-control a.past-selection-control.shown > i::before {
        content: "\f068" !important;
    }
</style>
@endsection

@section('script')
<script src="{{asset('vendors/js/jquery.maskedinput.min.js')}}"></script>
<script src="{{asset('vendors/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/validator.js')}}"></script>
<script src="{{asset('vendors/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('template/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{asset('js/views/customer/selections.js')}}"></script>
<script src="{{asset('js/views/customer/cards.js')}}"></script>
<script src="{{asset('js/views/customer/billing.js')}}"></script>
<script src="{{asset('js/views/customer/profile.js')}}"></script>
<script src="{{asset('js/views/customer/delivery.js')}}"></script>
<script src="{{asset('js/views/customer/menu.js')}}"></script>
<script src="{{asset('js/views/customer/manageplan.js')}}"></script>
<script src="{{asset('js/views/customer/invoice.js')}}"></script>
<script src="{{asset('js/views/customer/active.js')}}"></script>
<script src="{{asset('js/views/customer/past.js')}}"></script>

<script type="text/javascript">
	var url = {
		deliveryTimeUrl: "{{url($deliveryTimeUrl)}}/",
        listAllPlansUrl: "{{url($listAllPlansUrl)}}",
        listAllInvoiceUrl:  "{{url($listAllInvoiceUrl)}}",
        cancellAllPlansUrl: "{{url($cancellAllPlansUrl)}}",
        cancellPlansUrl: "{{url($cancellPlansUrl)}}/",
        saveStopTillDateUrl: "{{url($saveStopTillDateUrl)}}/",
        saveStopAllTillDateUrl: "{{url($saveStopAllTillDateUrl)}}",
        cancelPausedDateUrl: "{{url($cancelPausedDateUrl)}}/",
        cardsUrl: "{{url($cardsUrl)}}",
        menuPageUrl: "{{url($menuPageUrl)}}/",
        nextDeliveryDateUrl: "{{url($nextDeliveryDateUrl)}}/",
        updateCardDefaultUrl: "{{url($updateCardDefaultUrl)}}",
        creditCardSaveUrl: "{{url($creditCardSaveUrl)}}",
        updateDeliveryUrl: "{{url($updateDeliveryUrl)}}",
        saveNewPlanUrl: "{{url($saveNewPlanUrl)}}",
        subscriptionIdsUrl: "{{url($subscriptionIdsUrl)}}",
        storeCouponUrl: "{{url($storeCouponUrl)}}",
        orderSubscriptionSummaryUrl: "{{url($orderSubscriptionSummaryUrl)}}",
        removeCouponUrl: "{{url($removeCouponUrl)}}",
        updatePlanUrl: "{{url($updatePlanUrl)}}",
        checkoutPlanUrl: "{{url('plan')}}/",
        futureDeliveryTimingScheduleUrl: "{{url($futureDeliveryTimingScheduleUrl)}}/",
        previousWeeksSubscriptionUrl: "{{url($previousWeeksSubscriptionUrl)}}",
        deliveryTimingsSettingsUrl: "{{url($deliveryTimingsSettingsUrl)}}/",
        menus: "{{url($menusUrl)}}",
        pastSub: "{{url($pastSubUrl).'?user_id='.Auth::user()->id}}",
        pastWeeks: "{{url($pastWeeksUrl)}}",
        pastMenus: "{{url($pastMenusUrl)}}",
    };
    
    const userId = "{{Auth::user()->id}}";

    var Config = {
        iHavePlans: "{{ count($myPlans) > 0 ? true : false }}"
    };

	$(document).ready( function() {
        
        Selections.setSaveSelectionUrl("{{url($saveSelectionUrl)}}/");

        Cards.init({
            creditCardSaveUrl: url.creditCardSaveUrl,
            updateCardDefaultUrl: url.updateCardDefaultUrl,
            cardsUrl: url.cardsUrl
        });

        Billing.init({
            billingInfoAddressSaveUrl: "{{url($billingInfoAddressSaveUrl)}}",
        });

        Profile.init({
            updateProfileUrl: "{{url($updateProfileUrl)}}",
            updatePasswordUrl: "{{url($updatePasswordUrl)}}"
        });

        Delivery.init({
            updateDeliveryUrl: url.updateDeliveryUrl
        });

        ManagePlan.init({
            storeCouponUrl: url.storeCouponUrl,
            orderSubscriptionSummaryUrl: url.orderSubscriptionSummaryUrl,
            removeCouponUrl: url.removeCouponUrl,
            updatePlanUrl: url.updatePlanUrl,
            deliveryTimingsUrl: url.deliveryTimeUrl,
            saveNewPlanUrl: url.saveNewPlanUrl,
            checkoutPlanUrl: url.checkoutPlanUrl,
            deliveryTimingsSettingsUrl: url.deliveryTimingsSettingsUrl
        });

        Menu.init();
        
        listAllPlans();
        listAllInvoices();
        Cards.loadCards();

        $("#card_expiration_date").mask("99/99");
        $("#card_cvc").mask("999");
        $("#card_number").mask("9999 9999 9999 9999");
        $(document).on('click','.radio-cardlist', function() {
            Cards.bindCreditCards();
        });

        setDeliveryTimings($('#delivery_zone_id').find('option:selected').val());
        $(document).on('bind change', '#delivery_zone_id', function() { 
            setDeliveryTimings($(this).find('option:selected').val());
        });

        $(document).on('change', '#subscription', function() { 
            Menu.init();
        });

        $(document).on('change', '.meals-selection', function() {
            $(this).closest('form').attr('data-changed',1);
        });

	});

	$('#rootwizard').bootstrapWizard({
        'nextSelector': '.btn-continue', 'previousSelector': '.button-previous',
        'lastSelector': '.btn-checkout',
        onTabClick: function(tab, navigation, index) {
        },
        onLast: function(tab, navigation, index) {
        	
        },
        onNext: function(tab, navigation, index) {
        	return false;
        }, onTabShow: function(tab, navigation, index) {
            var $total = navigation.find('li').length;
            var $current = index+1;
            var $percent = ($current/$total) * 100;
            $('#rootwizard .progress-bar').css({width:$percent+'%'});
    }});

    function getSubscriptionId() {
        return $('#subscription option:selected').val();
    }

    function saveChoices() {
        if (!Selections.isValid()) {
            return Alert.error('Error!','Please fill-up all highligted fields.');
        }
        Selections.process();
    }

    function cancelAllPlans() {
        Alert.confirm(
            'Cancel','Are you sure you want to cancel all plans?',
            'Yes',
            'No',
            function(instance, toast) {
                System.blockUI('#tab2');
                System.lazyLoading( function() {
                    System.setAjaxRequest(
                        url.cancellAllPlansUrl,
                        '',
                        'PATCH',
                        function(response) {
                            System.unblockUI();
                            if (parseInt(response) > 0) {
                                listAllPlans();
                                Menu.init();
                                Alert.success('Success!','Successfully Cancelled All Plans.', 'topRight');
                            }
                        },
                        function() {
                            System.unblockUI();
                            Alert.error('Error',System.errorTextMessage, 'topRight');
                        }
                    );
                });
            },
            function(instance, toast) {

            }
        );
    }

    function cancelPlan(_this, subscriptionId, subscriptionCycleId, subscriptionCycleStatus, deliveryDate) {

        const alertPaidMsg = (typeof subscriptionCycleStatus != 'undefined' && subscriptionCycleStatus != '' && subscriptionCycleStatus != null && subscriptionCycleStatus.toLowerCase() == 'paid') ? "Note: since you have already paid for this week's delivery, you will still receive your meals on " + deliveryDate + ". <br/>You will not be charged or receive orders from this time. " : '';
        
        Alert.confirm(
            'Cancel',alertPaidMsg + 'Are you sure you want to cancel the plan?',
            'Yes',
            'No',
            function(instance, toast) {
                System.blockUI($(_this).closest('.listing'));
                System.lazyLoading( function() {
                    System.setAjaxRequest(
                        url.cancellPlansUrl,
                        {subscriptionId: subscriptionId, subscriptionCycleId: subscriptionCycleId},
                        'PATCH',
                        function(response) {
                            System.unblockUI();
                            if (parseInt(response) > 0) {
                                listAllPlans();
                                Menu.init();
                                Alert.success('Success!','Successfully Cancelled Plan.');
                            }
                        },
                        function() {
                            System.unblockUI();
                            Alert.error('Error',System.errorTextMessage, 'topRight');
                        }
                    );
                });
            },
            function(instance, toast) {

            }
        );
    }

    function cancelPausedDate(_this, subscriptionId, subscriptionCycleId) {
        Alert.confirm(
            'Cancel','Are you sure you want to cancel the paused date?',
            'Yes',
            'No',
            function(instance, toast) {
                System.blockUI($(_this).closest('.listing'));
                System.lazyLoading( function() {
                    System.setAjaxRequest(
                        url.cancelPausedDateUrl,
                        {subscriptionId: subscriptionId, subscriptionCycleId: subscriptionCycleId},
                        'PATCH',
                        function(response) {
                            System.unblockUI();
                            if (parseInt(response) > 0) {
                                Alert.success('Success!','Successfully Cancelled Paused Date.');
                                listAllPlans();
                            }
                        },
                        function() {
                            System.unblockUI();
                            Alert.error('Error',System.errorTextMessage, 'topRight');
                        }
                    );
                });
            },
            function(instance, toast) {

            }
        );
        
    }

    function createNewPlan() {
        var id = $('#meal_plans_id option:selected').val();
        if (id == '') return;

        if (!Config.iHavePlans || Config.iHavePlans == false || Config.iHavePlans == '') {
            window.location.href = "{{url('plan')}}/"+id;
            return;
        }

        Alert.confirm(
            'New','Are you sure you want to add this new plan?',
            'Yes',
            'No',
            function(instance, toast) {
                System.blockUI('#new-plan-form');
                System.lazyLoading( function() {
                    System.setAjaxRequest(
                        url.addNewPlanUrl,
                        {mealPlansId: id},
                        'POST',
                        function(response) {
                            System.unblockUI();
                            if (response.success == true) {
                                listAllPlans();
                                Menu.init();
                                Alert.success('Success!',response.message);
                            } else {
                                Alert.error('Error',response.message, 'topRight');
                            }
                            $('#meal_plans_id').val('');
                        },
                        function() {
                            System.unblockUI();
                            $('#meal_plans_id').val('');
                            Alert.error('Error',System.errorTextMessage, 'topRight');
                        }
                    );
                });
            },
            function(instance, toast) {

            }
        );
    }

    function inputPause(_this, subscriptionId, subscriptionCycleId, subscriptionCycleStatus, deliveryDate) {

        var _this = $(_this);

        System.blockUI(_this);
        System.setAjaxRequest(
            url.futureDeliveryTimingScheduleUrl,
            {subscriptionId: subscriptionId, subscriptionCycleId: subscriptionCycleId, subscriptionCycleStatus: subscriptionCycleStatus, deliveryDate: deliveryDate},
            'GET',
            function(response) {
                System.unblockUI();
                stopPlanTil(_this, response);
            },
            function(error) {
                System.unblockUI();
            },
            'html',
            true
        );
    }

    var subscribeId = 0, stopAllPlans = false;

    function stopAllPlansTill(_this) {
        var _this = $(_this);   
        stopAllPlans = true;
        _this.hide();
        _this.parent().append($('#datepicker-wrapper').html());
        _this.parent().find('#date-container input.date').datepicker();
    }
    function stopPlanTil(_this, data) {
        var _this = $(_this);
        const viewSelectionContainer = _this.parent().find('#previous-selections-content-wrapper');        
        
        stopAllPlans = false;
        _this.hide();
        if (viewSelectionContainer.length > 0) {
            $(data).insertBefore(viewSelectionContainer);
        } else {
            _this.parent().append(data);
        }
        
    }

    function closeStopTillDate(_this) {
        var _this = $(_this);
        if (stopAllPlans) {
             _this.closest('.col-md-12')
                .find('.stopallplans').show();
            _this.closest('.col-md-12')
                .find('#date-container').remove();
        }
        else {
             _this.closest('.listing-body')
                .find('.stoptilldate').show();
            _this.closest('.listing-body')
                .find('#date-container').remove();
            subscriberId = 0;
        }
    }

    function closeStopAllTillDate(_this) {
        var _this = $(_this);
        _this.closest('.col-md-12')
            .find('.stopallplans').show();
        _this.closest('.col-md-12')
            .find('#date-container').remove();
    }

    function removeDate(_this) {
        var _this = $(_this);
        _this.closest('.listing-body')
            .find('#date-container').remove();
    }

    function closePausedDate(_this) {
        var _this = $(_this);
        _this.closest('.listing-body').find('.cancelpauseddate').hide();
    }

    function closeStopPlanTill(_this) {
        var _this = $(_this);
        console.log(_this.closest('.listing-body').html());
        _this.closest('.listing-body').find('.stoptilldate').hide();
    }

    function showPausedDate(_this) {
        var _this = $(_this);
        _this.closest('.listing-body').find('.cancelpauseddate').show();
    }

    function showStopPlanTill(_this) {
        var _this = $(_this);
        _this.closest('.listing-body').find('.stoptilldate').show();
    }

    function singleSaveStopTillDate(_this, subscriptionId, subscriptionCycleId, date, subscriptionCycleStatus, deliveryDate) {
        
        const pauseDate = (typeof date != 'undefined' && date != null && date != '' && date != 'Select date') ? $(_this).closest('div').find('select.date option[value="'+date+'"]').text() : $(_this).closest('div').find('select.date option:nth-child(2)').text();
        const alertPaidMsg = (typeof subscriptionCycleStatus != 'undefined' && subscriptionCycleStatus != '' && subscriptionCycleStatus != null && subscriptionCycleStatus.toLowerCase() == 'paid') ? "Note: since you have already paid for this week's delivery, you will still receive your meals on " + deliveryDate + ". <br/>You will not be charged or receive orders from this time  - until your next delivery date on " + pauseDate + ". " : "";

        Alert.confirm(
            'Pause', alertPaidMsg + 'Are you sure you want to pause this plan?',
            'Yes',
            'No',
            function(instance, toast) {
                System.blockUI($(_this).closest('.listing'));
                System.lazyLoading( function() {
                    System.setAjaxRequest(
                        url.saveStopTillDateUrl,
                        {date: pauseDate, subscriptionCycleId: subscriptionCycleId, subscriptionId: subscriptionId},
                        'PATCH',
                        function(response) {
                            if (response.success) {
                                Alert.success('Success!',response.message);
                                stopAllPlans = false;
                                listAllPlans();
                            } else {
                                Alert.error('Error!',response.message);
                            }
                            
                            System.unblockUI();
                        },
                        function() {
                            stopAllPlans = false;
                            Alert.error('Error',System.errorTextMessage, 'topRight');
                            System.unblockUI();
                        }
                    );
                });
            },
            function(instance, toast) {

            }
        );

        
    }

    function SaveStopAllTillDate(_this, date) {
        System.blockUI($('#date-container'));
        System.lazyLoading( function() {
            System.setAjaxRequest(
                url.saveStopAllTillDateUrl,
                {date: date},
                'PATCH',
                function(response) {
                    if (response.success) {
                        Alert.success('Success!',response.message);
                        stopAllPlans = false;
                        closeStopAllTillDate(_this);
                        listAllPlans();
                    } else {
                        Alert.error('Error!',response.message);
                    }
                    System.unblockUI();
                },
                function() {
                    stopAllPlans = false;
                    Alert.error('Error',System.errorTextMessage, 'topRight');
                    System.unblockUI();
                }
            );
        });
    }


    function setDelivery(id) {
    	System.setAjaxRequest(
    		url.nextDeliveryDateUrl+id,
    		'',
    		'GET',
    		function(response) {
    			var delivery_timings_id = $('#delivery_timings');
    			delivery_timings_id.html(response);
    		},
            function(error) {
                console.log(error);
            },'html', true
    	);
	}   

    function setDeliveryTimings(id) {
        System.setAjaxRequest(
            url.deliveryTimeUrl+id,
            '',
            'GET',
            function(response) {
                var delivery_timings_id = $('#delivery_zone_timings_id');
                var options = '<option value="">Choose Delivery Time</option>';
                for (var i in response) {
                    options += "<option "+(getCurrentDeliveryZoneTimingId() == response[i].id ? 'selected' :  (response.length <= 1 ? 'selected' : '') )+" value='"+response[i].id+"'>"+response[i].date+"</option>";
                }

                delivery_timings_id.html(options);
            }
        );
    }   

    function listAllPlans() {
        hiddenManagePlanMainButtonIfNoActivePlans();
        System.lazyLoading( function() {
            System.setAjaxRequest(
                url.listAllPlansUrl,
                '',
                'GET',
                function(response) {
                   $('#listing-wrapper').html(response);
                   hiddenManagePlanMainButtonIfNoActivePlans();
                },
                function() {

                },
                'html', true
            );
        });
    }

    function listAllInvoices() {
        System.lazyLoading( function() {
            System.setAjaxRequest(
                url.listAllInvoiceUrl,
                '',
                'GET',
                function(response) {
                   $('#invoices-listing-wrapper').html(response);
                },
                function() {

                },
                'html', true
            );
        });
    }

    function getCurrentDeliveryZoneTimingId() {
        return $('#current_delivery_zone_timings_id').val();
    }

    function hiddenManagePlanMainButtonIfNoActivePlans() {
        if ($('#listing-wrapper  .listing-body').length == 0) {
            $('.btn-main-button, .btn-save-choices').fadeOut();
        } else {
            $('.btn-main-button, .btn-save-choices').fadeIn();
        }
    }

    
</script>


<script type="text/javascript">
    $(document).ready(function() {

        $('.nav-link a').click(function(e){
            e.preventDefault();
            let href = $(this).attr('href');
            console.log($(href).attr('tab-title'))
            $("title").text($(href).attr('tab-title')); 
                
        });

    });
</script>
@endsection

@section('content')

@endsection