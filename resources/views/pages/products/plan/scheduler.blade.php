@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('product-plans-scheduler'))

@section('content')

<div class="row">
    <div class="col-lg-7">

        <div class="row">
            <div class="col-sm-4" style="display: none;">
                <span>Active Cycle: <strong>{{$batch}}</strong></span> <br />
                <span>Next Active Cycle: <strong>{{$batch+1}}</strong></span>
            </div>
            <div class="col-sm-5">
            </div>
            <div class="col-sm-3" style="display: none;">
                <select class="form-control" id="batch" onchange="loadByBatch($('#batch option:selected').val())">
                    <option value="">All Cycles</option>
                    @foreach($batchList as $row)
                    <option value="{{$row->batch}}">Cycle {{$row->batch}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="m-portlet m-portlet--responsive-tablet-and-mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon">
                            <i class="flaticon-clipboard"></i>
                        </span>
                        <h3 class="m-portlet__head-text m--font-brand">
                            Meals Plan Scheduler
                        </h3>
                    </div>
                </div>

                <div class="m-portlet__head-tools">
                    <div class="btn-group m-btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">All
                        </button>
                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 34px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <a class="dropdown-item text-success btn-dropdown-item" href="#" data-value="1" data-color="btn-success">Active</a>
                            <a class="dropdown-item text-warning btn-dropdown-item" href="#" data-value="0" data-color="btn-warning">Future Cycle</a>
                            <a class="dropdown-item text-info btn-dropdown-item" href="#" data-value="all" data-color="btn-info">All</a>

                        </div>
                    </div>
                </div>
            </div>

            <div class="m-portlet__body" id="list-container">

            </div>
        </div>
    </div>
    <!--/.col-->
    <div class="col-lg-5" id="manage-meals-status-wrapper">
    </div>
</div>
<!--/.row-->

@endsection

@section('css')
    <link href="{{asset('vendors/css/select2.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/template/custom/datatables/datatables.bundle.css') }}">
    <style type="text/css">
        .container-fluid .dataTables_wrapper {
            padding: 10px !important;
       }
    </style>
@endsection
@section('script')
    <script src="{{ asset('/template/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/select2.min.js')}}"></script>

    <script type="text/javascript">
        $(function(){
          $('.datatable').DataTable({responsive:true});
          // $('.datatable').css({'border-collapse':'collapse !important'});
          $('.datatable').attr('style', 'border-collapse: collapse !important');
        });
    </script>
    <script type="text/javascript">
        
        var cycleId;
        var url = {
            manageCycleMealsUrl: "{{url($manageCycleMealsUrl)}}/",
            saveMealsChangeStatusUrl: "{{url($saveMealsChangeStatusUrl)}}/",
            listPlansScheduleUrl: "{{url($listPlansScheduleUrl)}}",
        }

        $('.btn-dropdown-item').click( function() {
            var _this = $(this);
            var btn = _this.parent().parent().find('.dropdown-toggle');
            
            loadMasterList(_this.attr('data-value'), $('#batch option:selected').val(), function() {
                btn.removeClass('btn-primary');
                btn.removeClass(btn.attr('data-color'));
                btn.addClass(_this.attr('data-color'));

                btn.attr('data-action',_this.attr('data-value'));
                btn.attr('data-color',_this.attr('data-color'));
                btn.text(_this.text());
            });
        });

        $(document).on('click','#add_all', function() {
            if($(this).is(':checked')) {
                $('#meal_ids_add option').attr('selected',true).parent().trigger('change');
            } else {
                $('#meal_ids_add option').attr('selected',false).parent().trigger('change');
            }
        });

        function loadByBatch(batch) {
            loadMasterList($('#btn-status').attr('data-action'), batch);
        }

        function loadMasterList(status, batch, __callback) {
            var status = status == undefined ? 'all' : status;
            var batch = batch == undefined ? '' : batch;
            var container = $('#list-container');
            System.blockUI('#list-container');
            System.lazyLoading( function() {
                System.setAjaxRequest(
                    url.listPlansScheduleUrl,
                    {status: status,batch: batch},
                    'GET',
                    function(response) {
                        cycleId = '';
                        $('#manage-meals-status-wrapper').html('');
                        System.unblockUI();
                        container.html(response);
                        $('.datatable').dataTable({responsive:true});
                        if (__callback != undefined) {
                            __callback();
                        }
                    },
                    function(response) {
                        System.unblockUI();
                        Alert.error('Error!',response);
                    },
                    'HTML',true
                );
            });
        }

        $(document).ready( function() {

            $(document).on('click','.btn-change-click', function() {
                $('.btn-change-click').addClass($(this).attr('data-ckd-class'));
                $(this).addClass($(this).attr('data-ck-class'));
                $(this).removeClass($(this).attr('data-ckd-class'));
            });

            $(document).on('submit','#scheduler-form', function() {

                if (cycleId == '' || cycleId == undefined) return false; 

                var data =  {
                    cycle_id: cycleId,
                    meal_ids_add: $('#meal_ids_add').val(),
                    meal_ids_remove: $('#meal_ids_remove').val(),
                };
                
                System.setAjaxRequest(
                    url.saveMealsChangeStatusUrl+cycleId,
                    data,
                    'PATCH',
                    function(response) {
                        if (response.status == 200)
                        {
                            if (response.success == true) {
                                Alert.success('Saved!', response.message);
                            }
                        }
                    },
                    function() {
                        Alert.error('Error',System.errorTextMessage, 'topRight');
                    }
                );

                return false;
            });

            loadMasterList();
        });

        function manageMealStatus(id) {
            cycleId = id;
            System.setAjaxRequest(
                url.manageCycleMealsUrl+id,
                '',
                'GET',
                function(response) {
                    $('#manage-meals-status-wrapper').html(response);
                    $('#meal_ids_add, #meal_ids_remove').select2({theme: "bootstrap", placeholder: "Select Meals"});
                },
                function() {
                    Alert.error('Error',System.errorTextMessage, 'topRight');
                },
                'HTML'
            );
        }

        function showMeals(url) {
            System.setAjaxRequest(
                url,
                '',
                'GET',
                function(response) {  
                    var options = '';   
                    for (var i in response) {
                        options += "<option value='"+response[i].id+"'>"+response[i].meal_name+"</option>"
                    } 
                    $('#meals_id').html(options);
                    $('#meals_id').select2({theme: "bootstrap", placeholder: "Select Meals"});
                },
                function() {
                    Alert.error('Error',System.errorTextMessage, 'topRight');
                }
            );
        }
        
    </script>
@endsection