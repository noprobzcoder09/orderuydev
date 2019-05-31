@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('customer-billing-issue'))

@section('content')
<div class="m-content">
    <div class="row">
        <div class="col-md-6">
        @include('errors.messages')
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="m-portlet m-portlet--responsive-tablet-and-mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="flaticon-internet"></i>
                            </span>
                            <h3 class="m-portlet__head-text m--font-brand">
                                Billing Issues
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="m-portlet__body card-body">
                    @include($view.'table')
                </div>
            </div>
        </div>
    </div>
</div>
<!--/.row-->

@include($view.'card-modal')

@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('/template/custom/datatables/datatables.bundle.css') }}">
@endsection

@section('script')
    
    <script src="{{ asset('/template/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendors/js/jquery.maskedinput.min.js')}}"></script>
    <script src="{{ asset('template/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/views/customer/admin/billing-issue.js') }}" type="text/javascript"></script>

    <script type="text/javascript">

        var url = {
            masterlistUrl: "{{url($masterlistUrl)}}",
            cardModalUrl: "{{url($cardModalUrl)}}",
            addNewCreditCardUrl: "{{url($addNewCreditCardUrl)}}",
            updateCardDefaultUrl: "{{url($updateCardDefaultUrl)}}",
            billNowUrl: "{{url($billNowUrl)}}",
            cancelForTheWeekUrl: "{{url($cancelForTheWeekUrl)}}",
            cancelCustomerUrl: "{{url($cancelCustomerUrl)}}"
            
        }

        $(document).ready( function() {
            loadMasterList();
        });


        var table, filterContainer;
        function loadMasterList(status) {
            filterContainer = $('#table-customer-filter-container');

            System.lazyLoading( function() {

                if (table != undefined || table != null) {
                    table.destroy();
                }

                table = $('.datatable').DataTable({
                    pageLength: 10,
                    bFilter: false, 
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: url.masterlistUrl+'?status=billing issue',
                    autoWidth: false,
                    columns: [
                        { "data": "name" },
                        { "data": "email" },
                        { "data": "mobile_phone" },
                        { "data": "plan_name" },
                        { "data": "price" },
                        { "data": "billing_attempt" },
                        { "data": "weeks_active" },
                        { "data": "billing_attempt" },
                        { "data": "user_id",
                            "render": function ( data, type, row ) {

                                var html = new Array();
                                // html.push();
                                // html.push('<div class="btn-group">');
                                // html.push('<button class="btn btn-radius-none btn-secondary dropdown-toggle" type="button" id="groupbuttonadvance-'+row.user_id+' data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>');
                                // html.push('<div class="dropdown-menu">');
                                // html.push('<a href="" class="m-btn dropdown-item">Manage Subscriptions</a>');
                                // html.push('<a href="javascript:;" class="m-btn dropdown-item" onclick="BillingIssue.showCardModal('+row.user_id+','+row.id+')">Update Card</a>');
                                // html.push('<a href="javascript:;" class="m-btn dropdown-item" onclick="BillingIssue.billNow(this, '+$row->user_id+')">Bill Now</a>');
                                // html.push('<a href="javascript:;" class="m-btn dropdown-item" onclick="BillingIssue.cancelForTheWeek(this, '+row.user_id+','+row.subscription_cycle_ids+')">Cancel For Week Only</a>');
                                // html.push('<a href="javascript:;" class="m-btn dropdown-item" onclick="BillingIssue.cancelCustomer(this, '+row.user_id+')">Cancel Customer</a>');
                                // html.push('</div>');
                                // html.push('</div>');
                                
                                return html.join('');
                            }
                        },
                    ],
                    initComplete: function(settings, json) {
                        
                    }
                });
            });
        }

        var dataTableFilterContainer = '.dataTables_wrapper > .row:first-child > .col-md-6:nth-child(2)';

    </script>
@endsection