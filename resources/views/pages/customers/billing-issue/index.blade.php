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
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <div class="m-portlet__head-title">
                                    <span class="m-portlet__head-icon">
                                        <i class="flaticon-internet"></i>
                                    </span>
                                    <h3 class="m-portlet__head-text m--font-brand">
                                        Billing Issues
                                    </h3>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <select class="form-control" name="cycle_id" id="cycle_id">
                                    <option value=''>Filter By Delivery Date</option>
                                    <?php $i = 0; ?>
                                    @foreach($previousCycles as $row)
                                    <option {{$i ==  0 ? 'selected' : ''}} value="{{$row->id}}">{{date('l jS F Y', strtotime($row->delivery_date))}}</option>
                                    <?php $i++;?>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="m-portlet__body card-body">
                    <p class="text-center">No Record/s found.</p>
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
            cancelSubscriptionUrl: "{{url($cancelSubscriptionUrl)}}"
            
        }

        var Form = {
            getCycleId: function() {
                return $('#cycle_id option:selected').val();
            },
            isEmptyCycleId: function() {
                if (Form.getCycleId() == '' || Form.getCycleId() == undefined || Form.getCycleId() == null) {
                    return true;
                }
                return false;
            }
        }

        var Element = {
            cycleId: '#cycle_id'
        }

        $(document).ready( function() {
            loadMasterList();

            $(Element.cycleId).change( function() {
                loadMasterList();
            });
        });


        function loadMasterList() {
            if (Form.isEmptyCycleId()) return;

            var container = $('.card-body');
            System.blockUI('.card-body');
            System.lazyLoading( function() {
                System.setAjaxRequest(
                    url.masterlistUrl,
                    {status: 'billing issue', cycle_id: Form.getCycleId()},
                    'GET',
                    function(response) {
                        System.unblockUI();
                        container.html(response);
                        $('.datatable').dataTable({responsive:true});
                    },
                    function(response) {
                        System.unblockUI();
                        Alert.error('Error!',response);
                    },
                    'HTML',true
                );
            });
        }

    </script>
@endsection