@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('edit-customer', $id))

@section('content')

<div class="m-content">

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="la la-user"></i>
                            </span>
                            <h3 class="m-portlet__head-text m--font-brand">
                                Customer Information
                            </h3>
                        </div>
                    </div>
                    
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <a href="https://ru582.infusionsoft.com/Contact/manageContact.jsp?view=edit&ID={{$ins_contact_id}}&lists_sel=orders" target="_blank" class="m-portlet__nav-link btn btn-success m-btn m-btn--pill m-btn--air">
                                   <i class="la la-eye"></i> View Infusion Contact
                                </a>
                            </li>
                            <!-- 
                            <li class="m-portlet__nav-item">
                                <a href="{{url($urlNew)}}/find-email" class="m-portlet__nav-link btn btn-success m-btn m-btn--pill m-btn--air">
                                   <i class="la la-plus"></i> Customer
                                </a>
                            </li> -->
                            <li class="m-portlet__nav-item">
                                <a href="javascript:;" dusk="reset-password" onclick="Customer.resetPassword({{$id}})" class="m-portlet__nav-link btn btn-success m-btn m-btn--pill m-btn--air">
                                   <i class="la la-key"></i> Reset Password
                                </a>
                            </li>
                            <li class="m-portlet__nav-item">
                                <a href="/customers/{{$id}}/audit/logs" class="m-portlet__nav-link btn btn-success m-btn m-btn--pill m-btn--air">
                                   <i class="icon-list"></i> View Customer Logs
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">

            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="flaticon-profile-1"></i>
                            </span>
                            <h3 class="m-portlet__head-text m--font-brand">
                                Profile
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <a href="javascript:;" onclick="editCustomer()" class="m-portlet__nav-link btn btn-success m-btn m-btn--pill m-btn--air">
                                   <i class="fa flaticon-edit"></i> Edit
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body portlet-relative">
                    <!-- <div class="portlet-float-cover">
                        <a href="javascript:;" class="btn btn-accent m-btn m-btn--icon m-btn--pill m-btn--air" title="Edit Customer" onclick="editCustomer()">
                            <span>
                                <i class="fa flaticon-edit"></i>
                                <span>
                                    Edit
                                </span>
                            </span>
                        </a>
                    </div> -->
                    <div class="row stacked">
                        <div class="col-sm-12" id="customer-container">
                            @include($view.'table-customer')
                        </div>
                    </div>
                    
                </div>
            </div>

        </div>    

        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="flaticon-clock"></i>
                            </span>
                            <h3 class="m-portlet__head-text m--font-brand">
                                Delivery Zone Schedule
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <a href="javascript:;" onclick="editDeliveryZoneTiming(this)" class="m-portlet__nav-link btn btn-success m-btn m-btn--pill m-btn--air">
                                   <i class="fa flaticon-edit"></i> Edit
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body portlet-relative">
                    <!-- <div class="portlet-float-cover">
                        <a class="btn btn-accent m-btn m-btn--icon m-btn--pill m-btn--air" href="javascript:;" title="Edit Delivery Zone Timing" >
                            <span>
                                <i class="fa flaticon-edit"></i>
                                <span>
                                    Edit
                                </span>
                            </span>
                        </a>
                    </div> -->
                    <div class="row stacked">
                        <div class="col-sm-12" id="delivery-container">
                            @include($view.'table-delivery')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
             <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="flaticon-bag"></i>
                            </span>
                            <h3 class="m-portlet__head-text m--font-brand">
                                Billing
                            </h3>
                        </div>
                    </div>

                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <a href="javascript:;" onclick="addNewCard()" class="m-portlet__nav-link btn btn-success m-btn m-btn--pill m-btn--air">
                                   <i class="la la-plus"></i> New Card
                                </a>
                            </li>
                        </ul>
                    </div> 
                </div>
                <div class="m-portlet__body portlet-relative">
                    <div class="row stacked">
                        <div class="col-sm-12" id="billing-container">
                            <div id="card-loader" style="display: none; text-align: center;"><i class="fa fa-spinner fa-spin"></i> <br>Loading Cards </div>
                            @include($view.'table-billing')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="la la-check-circle"></i>
                            </span>
                            <h3 class="m-portlet__head-text m--font-brand">
                                Active Subscriptions
                            </h3>
                        </div>
                    </div>

                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <a href="javascript:;" dusk="add-plan" onclick="ManagePlan.modalCreate()" class="m-portlet__nav-link btn btn-success m-btn m-btn--pill m-btn--air">
                                   <i class="la la-plus"></i> Plan
                                </a>
                            </li>                            
                        </ul>
                    </div>                    
                </div>
                <div class="m-portlet__body">
                    
                    @include($view.'table-active-subscriptions')
                    
                </div>
            </div>


            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="flaticon-list"></i>
                            </span>
                            <h3 class="m-portlet__head-text m--font-brand">
                                Past Subscriptions
                            </h3>
                        </div>
                    </div>                  
                </div>
                <div class="m-portlet__body">
                    
                    @include($view.'table-past-subscriptions')
                    
                </div>
            </div>

            <div class="m-portlet m-portlet--responsive-mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="la la-file-text"></i>
                            </span>
                            <h3 class="m-portlet__head-text m--font-brand">
                                Invoices
                            </h3>
                        </div>
                    </div>  

                    <!-- <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <div class="input-group">
                                    <span class="input-group">
                                        <input class="form-control credit-card-input" id="searchBySubId" name="searchBySubId"  type="text" placeholder="Enter Subscription ID" value="">
                                        <span onclick="searchBySubId($('#searchBySubId').val())" class="input-group-text input-group-text-remove-bg br-0"><i class="fa fa-search"></i></span>
                                    </span>
                                </div>
                            </li>
                        </ul>
                    </div>  -->
                                    
                </div>
                <div class="m-portlet__body">

                    @include($view.'table-invoices')
                    
                </div>
            </div>
        </div> 

        </div>
    </div>

</div>

<!--/.row-->
@include($view.'card-modal')
@include($view.'invoice-modal')
@include($view.'selections-modal')
@include($view.'customer-modal')
@include($view.'delivery-zt-modal')
@include($view.'subscription-modal')
@include($view.'addmenuprevweek-modal')

@endsection


@section('css')
<link rel="stylesheet" href="{{ asset('template/custom/datatables/datatables.bundle.css') }}">

<link href="{{asset('vendors/css/select2.min.css')}}" rel="stylesheet">
<style type="text/css">
    .custom-table table {
        width: 100%;
    }

    .custom-table table tbody tr td:nth-child(1):after{
        content: ':';
    } 

    .custom-table table tbody tr td:nth-child(1), .custom-table table tbody tr td:nth-child(3) {
        font-weight: bold;
        color: #333;
        width: 15%;
        text-align: right;
    } 

    .custom-table table tbody tr td:nth-child(3):after{
        content: ':';
    } 

    .custom-table table tbody tr td.none:after {
        content: '';
    }

    .container-fluid .dataTables_wrapper {
        padding: 0px !important;
    }

    td.selections-control  a.selection-control > i::before {
        content: "\f067" !important;
        cursor: pointer;
    }

    td.past-selections-control  a.past-selection-control > i::before {
        content: "\f067" !important;
        cursor: pointer;
    }

    tr.shown td.selections-control  a.selection-control.shown > i::before {
        content: "\f068" !important;
    }

    tr.shown td.past-selections-control  a.past-selection-control.shown > i::before {
        content: "\f068" !important;
    }

    td.selections-control  a.invoice-control > i::before {
        content: "\f067" !important;
        cursor: pointer;
    }

    td.past-selections-control  a.invoice-control > i::before {
        content: "\f067" !important;
        cursor: pointer;
    }

    a.invoice-selection-control > i::before {
        content: "\f067" !important;
        cursor: pointer;
    }

    tr.shown  a.invoice-selection-control.shown > i::before {
        content: "\f068" !important;
    }

    tr.shown td.selections-control > a.invoice-control.shown > i::before {
        content: "\f068" !important;
    }

    tr.shown td.past-selections-control > a.invoice-control.shown > i::before {
        content: "\f068" !important;
    }

    #table-active-subs thead tr th{
        border: 0 none !important;
    }

    #table-past-subs thead tr th{
        border: 0 none !important;
    }

    #table-order-summary {
        width: 100%;
    }

    #table-order-summary tbody tr td {
        border-bottom: 1px solid #ccc !important;
    }

    #table-order-summary tbody tr:first-child td {
        border-bottom: 1px solid #000 !important;
        border-top:  0 none !important;
    }

    #table-order-summary tbody tr:last-child td {
        border-bottom:  0 none !important;
    }

    #table-order-summary tbody tr td {
        padding: 10px;
    }

    #table-order-summary tbody tr td.product {
        padding-left: 30px;
    }
</style>

@endsection


@section('script')

<script src="{{asset('vendors/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/validator.js')}}"></script>

<script src="{{asset('vendors/js/select2.min.js')}}"></script>
<script src="{{asset('vendors/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/jquery.maskedinput.min.js')}}"></script>

<script src="{{asset('js/views/customer/admin/active.js')}}"></script>
<script src="{{asset('js/views/customer/admin/past.js')}}"></script>
<script src="{{asset('js/views/customer/admin/customer.js')}}"></script>
<script src="{{asset('js/views/customer/admin/manageplan.js')}}"></script>
<script src="{{asset('js/views/customer/admin/invoice.js')}}"></script>
<script src="{{asset('js/views/customer/admin/card.js')}}"></script>
<script src="{{asset('js/views/customer/admin/previousweek.js')}}"></script>
<script src="{{asset('js/views/customer/admin/previousmenuselections.js')}}"></script>



<script src="{{asset('/template/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>

<script type="text/javascript">

    var url = {
        updateCustomerProfileUrl: "{{url($updateCustomerProfileUrl)}}/{{$id}}",
        updateCustomerDeliveryUrl: "{{url($updateCustomerDeliveryUrl)}}/{{$id}}",
        createSubscriptionUrl: "{{url($createSubscriptionUrl)}}",
        activeSub: "{{url('/customers/subscriptions/active')}}?user_id={{$id}}",
        pastSub: "{{url('/customers/subscriptions/past')}}?user_id={{$id}}",
        weeks: "{{url('/customers/subscriptions/weeks')}}",
        menus: "{{url('/customers/subscriptions/menus')}}",
        pastMenus: "{{url('/customers/subscriptions/past-menus')}}",
        pastWeeks: "{{url('/customers/subscriptions/past-weeks')}}",
        invoicesMenu: "{{url('/customers/subscriptions/invoice-menu')}}",
        invoices: "{{url('/customers/subscriptions/invoices')}}",
        cardUrl: "{{url('getcards')}}",
        cancelUrl: "{{url($cancelUrl)}}/",
        resetPasswordUrl: "{{url($resetPasswordUrl)}}/",
        pauseUrl: "{{url($pauseUrl)}}/",
        playUrl: "{{url($playUrl)}}/",
        createdCardUrl: "{{url($createdCardUrl)}}",
        futureDeliveryTimingScheduleUrl: "{{url($futureDeliveryTimingScheduleUrl)}}/",
        deliveryTimeUrl: "{{url($deliveryTimeUrl)}}/",
        saveNewPlanUrl: "{{url($saveNewPlanUrl)}}/{{$id}}",
        saveNewPlanUrlWithBilling: "{{url($saveNewPlanUrlWithBilling)}}/{{$id}}",
        subscriptionIdsUrl: "{{url($subscriptionIdsUrl)}}",
        storeCouponUrl: "{{url($storeCouponUrl)}}/{{$id}}",
        orderSubscriptionSummaryUrl: "{{url($orderSubscriptionSummaryUrl)}}",
        removeCouponUrl: "{{url($removeCouponUrl)}}",
        updatePlanUrl: "{{url($updatePlanUrl)}}",
        updateStatusUrl: "{{url($updateStatusUrl)}}",
        addMenuPrevWeekContentUrl:"{{ url($addMenuPrevWeekContentUrl)}}",
        addMenuPrevWeekOrderSubscriptionSummaryUrl: "{{url($addMenuPrevWeekOrderSubscriptionSummaryUrl)}}",
        addMenuPrevWeekOrderUpdatePlanUrl: "{{url($addMenuPrevWeekOrderUpdatePlanUrl)}}",
        addMenuPrevWeekOrderUrl: "{{url($addMenuPrevWeekOrderUrl)}}/{{$id}}",
        addMenuPrevWeekOrderWithBillingUrl: "{{url($addMenuPrevWeekOrderWithBillingUrl)}}/{{$id}}",
        pastMenusPreviousSubscriptionsUrl: "{{url($pastMenusPreviousSubscriptionsUrl)}}",
        updatePreviousSubscriptionsUrl: "{{url($updatePreviousSubscriptionsUrl)}}",
    };

    var customerForm = '#customer-form';
    var deliveryForm = '#delivery-form';
    var userId = "{{$id}}";
    var appLink = "{{\Helper::getAppLink()}}";

    var Config = {
        deliveryZoneTimingId: "{{$profile->delivery_zone_timings_id ?? 0}}",
        deliveryZoneId:  "{{$delivery_zone_id ?? 0}}"
    };

    $(document).ready( function() {
        loadCards('#billing-container #card-loader');

        Card.init({
            createdCardUrl: url.createdCardUrl
        });

        ManagePlan.init({
            storeCouponUrl: url.storeCouponUrl,
            orderSubscriptionSummaryUrl: url.orderSubscriptionSummaryUrl,
            removeCouponUrl: url.removeCouponUrl,
            updatePlanUrl: url.updatePlanUrl,
            deliveryTimingsUrl: url.deliveryTimeUrl,
            saveNewPlanUrl: url.saveNewPlanUrl,
            saveNewPlanUrlWithBilling: url.saveNewPlanUrlWithBilling,
            checkoutPlanUrl: url.checkoutPlanUrl,
            deliveryZoneTimingId: Config.deliveryZoneTimingId
        });

        PreviousWeekMenu.init();
        setDeliveryTimings(Config.deliveryZoneId);

        $('#coupons').on('select2:unselecting', function (e) {
            ManagePlan.removeCoupon(e.params.args.data.text);
        });

        $('#coupons').on('select2:selecting', function (e) {
            var success = ManagePlan.storeCoupon(e.params.args.data.text);
            if (!success) {
                $('#coupons').find('option[value="'+e.params.args.data.id+'"]').prop('selected',false);
                $('#coupons').trigger('change.select2');
            }
        });

        $('#coupons').on('select2:select', function (e) {
            
        });
    });

    $(function (){

        Validator.init(customerForm, {
            rules: {
                first_name: 'required',
                last_name: 'required',
                phone: 'required',
                // email: {
                    // required: true
                // },
                address1: 'required',
                country: 'required',
                state: 'required',
                postcode: 'required',
                suburb: 'required'
            },
            messages: {
                email: {
                    required: 'Please enter email.',
                    email: 'Please enter a valid email address.',
                    remote: "Email address is already registered."
                },
                firstname: {
                    required: 'Please enter a first name.'
                },
                lastname: {
                    required: 'Please enter a last name.'
                },
                phone: {
                    required: 'Please enter a phone.'
                },
                address1: {
                    required: 'Please enter a address 1.'
                },
                country: {
                    required: 'Please enter a country.'
                },
                state: {
                    required: 'Please enter a state.'
                },
                postcode: {
                    required: 'Please enter a postal code.',
                    number: 'Please a number only.'
                },
                suburb: {
                    required: 'Please enter a suburb.'
                },
            },
            submitHandler: function () {
                System.blockUI($(customerForm));
                System.lazyLoading( function() {
                    System.setAjaxRequest(
                        url.updateCustomerProfileUrl,
                        $(customerForm).serialize()+'&state_desc='+$('#state option:selected').text(),
                        'PATCH',
                        function(response) {
                            System.unblockUI();
                            if (response.status == 200)
                            {
                                if (response.success) {
                                    $('#customer-modal').modal('hide');
                                    $('#customer-container').html(response.html);
                                    Alert.success('Success!', response.message);
                                } else {
                                    Alert.error('Error!', response.message);
                                }
                            } else {
                                Alert.error('Error!', response.message);
                            }
                        },
                        function(error) {
                            System.unblockUI();
                            Alert.error('Error!', System.errorTextMessage);
                        }
                    );
                });
                return false;
            }
        });

        Validator.init(deliveryForm, {
            rules: {
                delivery_zone_timings_id: 'required',
            },
            messages: {
                delivery_zone_timings_id: {
                    required: 'Please enter a delivery zone timing.',
                },
            },
            submitHandler: function () {
                System.blockUI($(deliveryForm));
                System.setAjaxRequest(
                    url.updateCustomerDeliveryUrl,
                    $(deliveryForm).serialize(),
                    'PATCH',
                    function(response) {
                        System.unblockUI();
                        if (response.success) {
                            $('#delivery-zt-modal').modal('hide');
                            $('#delivery-container').html(response.html);
                            Alert.success('Success!', response.message);
                        } else {
                            Alert.error('Error!', response.message);
                        }
                    },
                    function(error) {
                        System.unblockUI();
                        Alert.error('Error!', System.errorTextMessage);
                    }
                    );
                return false;
            }
        });
    });

    function loadCards(_this, is_clicked = false) {
        var cardForm = $('#cardslist');
        var cardElement = $('.card_id');
        var cards = '', cardsOptions = '';

        if (_this != undefined) {
            $(_this).show();
            if (is_clicked) {
                $(_this).addClass('fa-spin');
            }
        }

        System.setAjaxRequest(
            url.cardUrl,
            {id: userId},
            'GET',
            function(response) {
                var defaultCard = response.default != undefined ? response.default : '';

                cardForm.html("<p>Card loading...</p>");

                for (var i in response.cards) {
                    cards += "<label for='my_card_"+response.cards[i].id+"'>Card************"+response.cards[i].last4+" "+(response.cards[i].id == defaultCard ? '<sup>Default</sup>' : '')+"</label><br />";

                    cardsOptions += "<option "+(response.cards[i].id == defaultCard ? 'selected' : '')+" value='"+response.cards[i].id+"'>Card************"+response.cards[i].last4+" "+(response.cards[i].id == defaultCard ? '<sup>Default</sup>' : '')+"</option>";
                }


                if (response.cards.length <= 0) {
                    cards = '<p>No Records.</p>';
                }

                cardForm.html(cards);
                cardElement.html(cardsOptions);

                if (_this != undefined) {
                    $(_this).hide();
                    if (is_clicked) {
                        $(_this).removeClass('fa-spin');
                    }
                }
            },
            function() {
                cardForm.html("<p>Could not retrieve your records. Please <a href='javascript:;' onclick='loadCards()'>reload</a> to try again.</p>");
                cardElement.html("<option value=''>Failed to load cards. Please click the reload button.</option>");
                if (_this != undefined) {
                    $(_this).show();
                    if (is_clicked) {
                        $(_this).removeClass('fa-spin');
                    }
                }
            }, 'json', true
        );
    }

    function setDeliveryTimings(id) {
        System.setAjaxRequest(
            url.deliveryTimeUrl+id,
            '',
            'GET',
            function(response) {
                var delivery_timings_id = $('#manageplan_delivery_zone_timings_id');
                var options = '<option value="">Choose Delivery Time</option>';
                for (var i in response) {
                    options += "<option "+(Config.deliveryZoneTimingId == response[i].id ? 'selected' : '')+"  "+(response.length <= 1 ? 'selected' : '')+" value='"+response[i].id+"'>"+response[i].date+"</option>";
                }

                delivery_timings_id.html(options);
            }
        );
    }

    </script>

    <script type="text/javascript">    
        var table;
        function addNewActiveSubscription() {
            $('#active-subscription-modal').modal('show');
        }

        function hideAddNewActiveSubscription() {
            $('#active-subscription-modal').modal('hide');
        }

        function editDeliveryZoneTiming() {
            $('#delivery-zt-modal').modal('show');
        }

        function editCustomer() {
            $('#customer-modal').modal('show');
        }

        function addNewCard(_this) {
            $('#creditcard-modal').modal('show');
            
            $("#card_expiration_date").mask("99/99");
            $("#card_cvc").mask("999");
            $("#card_number").mask("9999 9999 9999 9999");
        }

        function invoice(_this) {
            $('#invoice-modal').modal('show');
        }

        function selections(_this) {
            $('#selections-modal').modal('show');
        }

        function pause(_this, id) {
            var _this = $(_this);
            System.addSpinner(_this.find('i'), 'icon-control-pause');
        }
    </script>
@endsection