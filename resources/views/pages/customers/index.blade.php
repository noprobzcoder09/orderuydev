@extends('layouts.app')

@if(Route::current()->getName() == 'dashboard' || Route::current()->getName() == 'dashboard.home')
    @section('breadcrumbs', Breadcrumbs::render('home'))
@else
    @section('breadcrumbs', Breadcrumbs::render('customers'))
@endif

@section('css')
<link rel="stylesheet" href="{{ asset('/template/custom/datatables/datatables.bundle.css') }}">

<style type="text/css">
    #table-customer-filter-container {
        display: none;
    }
</style>
@endsection


@section('content')

<div class="m-content">

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="m-portlet m-portlet--responsive-tablet-and-mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="la la-repeat"></i>
                            </span>
                            <h3 class="m-portlet__head-text m--font-brand">
                                Subscriptions
                            </h3>
                        </div>
                    </div>
                    
                    <div class="m-portlet__head-tools">
                        <div class="btn-group m-btn-group">
                            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Active
                            </button>
                            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 34px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <a class="dropdown-item text-success btn-dropdown-item" href="#" data-value="active" data-color="btn-success">Active</a>
                                <a class="dropdown-item text-warning btn-dropdown-item" href="#" data-value="billing issue" data-color="btn-warning">Billing Issue</a>
                                <a class="dropdown-item text-primary btn-dropdown-item" href="#" data-value="paused" data-color="btn-primary">Paused</a>
                                <a class="dropdown-item text-danger btn-dropdown-item" href="#" data-value="cancelled" data-color="btn-danger">Cancelled</a>
                                <a class="dropdown-item text-danger btn-dropdown-item" href="#" data-value="failed" data-color="btn-danger">Failed</a>
                                <a class="dropdown-item text-primary btn-dropdown-item" href="#" data-value="all" data-color="btn-primary">All</a>
                            </div>
                            <a href="{{url($url)}}/new/find-email" class="btn btn-success margin-left-10"><i class="fa fa-plus"></i>&nbsp;Add new</a>
                        </div>
                    </div>
                </div>

                <div class="m-portlet__body card-body">
                    @include($view.'tables')
                </div>
            </div>
        </div>
    </div>
</div>
<!--/.row-->
@include($view.'customer-filter')
@endsection

@section('script')
    <!-- <script src="{{asset('vendors/js/jquery.dataTables.min.js')}}"></script> -->
    <!--<script src="{{asset('vendors/js/dataTables.bootstrap4.min.js')}}"></script>  -->
    
    <script src="{{ asset('/template/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>    
    <script type="text/javascript">
      var a = [];

        var url = {
            masterlistUrl: "{{url($masterlistUrl)}}"
        }

        $(document).ready( function() {
            loadMasterList('active');

            $(document).on('submit','#form-customer-filter', function() {
                $('#filter-data').trigger('click');
                return false;
            });
        });

        $('.btn-dropdown-item').click( function() {
            var _this = $(this);
            var btn = _this.parent().parent().find('.dropdown-toggle');

            loadMasterList(_this.attr('data-value'), function() {
                btn.removeClass('btn-primary');
                btn.removeClass(btn.attr('data-color'));
                btn.addClass(_this.attr('data-color'));

                btn.attr('data-action',_this.attr('data-value'));
                btn.attr('data-color',_this.attr('data-color'));
                btn.text(_this.text());
            });

        });

        function editLink(userId) {
            window.location.href="{{url('customers/edit')}}"+"/"+userId;
        }

        var table, filterContainer;
        function loadMasterList(status, __callback, thisForm) {
            filterContainer = $('#table-customer-filter-container');
            var status = status == undefined ? '' : status;
            var thisForm = thisForm == undefined ? $('#form-customer-filter') : thisForm;
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
                    ajax: url.masterlistUrl+'?status='+status+'&'+thisForm.serialize(),
                    autoWidth: false,
                    columns: [
                        { "data": "name" },
                        { "data": "plan_name" },
                        { "data": "location_timing" },
                        { 
                            "data": "user_status",
                            "render": function ( data, type, row ) {
                                let badge = 'badge-warning';
                                switch(row.user_status.toLowerCase()){
                                    case 'active': 
                                        badge = 'badge-success'; 
                                        break;
                                    case 'failed':
                                    case 'cancelled': 
                                        badge = 'badge-danger'; 
                                        break;
                                    case 'paused': 
                                        badge = 'badge-primary'; 
                                        break;
                                    default: 
                                        badge = 'badge-warning';
                                        break;
                                }
                                return '<span class="badge '+badge+'">' + row.user_status + '</span>';
                            }
                        },
                        { "data": "user_id" }
                    ],
                    columnDefs: [ {
                        targets: 4,
                        data: "user_id",
                        render: function(data, type, row, meta) {
                            return '<a class="btn btn-success" onclick="editLink('+data+')" href="javascript:;" title="View"><i class="fa fa-search-plus "></i></a>';
                        }
                    } ],
                    order: [[1, 'asc']],
                    initComplete: function(settings, json) {
                        $(dataTableFilterContainer).html(filterContainer.html());
                        $(dataTableFilterContainer).find('input[name="filter"]').val(
                            thisForm.find('input[name="filter"]').val()
                        );
                        $(dataTableFilterContainer).find('select[name="filter_type"]').val(
                            thisForm.find('select[name="filter_type"] option:selected').val()
                        );
                    }
                });
            });

            if (__callback != undefined &&  typeof __callback == 'function' ) {
                __callback();
            }
        }

        var dataTableFilterContainer = '.dataTables_wrapper > .row:first-child > .col-md-6:nth-child(2)';

    </script>
@endsection


