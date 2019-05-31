@extends('layouts.app')


@section('breadcrumbs', Breadcrumbs::render('product-meals'))


@section('css')
<link rel="stylesheet" href="{{ asset('/template/custom/datatables/datatables.bundle.css') }}">
<style type="text/css">
    .text-indent-20 {
        text-indent: 20px;
    }
    .container-fluid .dataTables_wrapper {
        padding: 10px !important;
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
                                <i class="flaticon-clipboard"></i>
                            </span>
                            <h3 class="m-portlet__head-text m--font-brand">
                                Masterlist
                            </h3>
                        </div>
                    </div>

                    <div class="m-portlet__head-tools">
                        <div class="btn-group m-btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Active
                            </button>
                            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 34px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <a class="dropdown-item text-success btn-dropdown-item" href="#" data-value="1" data-color="btn-success">Active</a>
                                <a class="dropdown-item text-warning btn-dropdown-item" href="#" data-value="0" data-color="btn-warning">Inactive</a>
                                <a class="dropdown-item text-info btn-dropdown-item" href="#" data-value="all" data-color="btn-info">All</a>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="m-portlet__body card-body">

                </div>
            </div>
        </div>
    </div>
</div>


<!--/.row-->
@endsection

@section('script')
    <script src="{{ asset('/template/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>

    <script type="text/javascript">
    
        var url = {
            masterlistUrl: "{{url($masterlistUrl)}}",
            listUrl: "{{url($listUrl)}}",
            deleteUrl: "{{url($deleteUrl)}}/"
        }

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

        function loadMasterList(status, __callback) {
            var status = status == undefined ? 'all' : status;
            var container = $('.card-body');
            System.blockUI('.card-body');
            System.lazyLoading( function() {
                System.setAjaxRequest(
                    url.listUrl,
                    {status: status},
                    'GET',
                    function(response) {
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
            loadMasterList();
        });
    </script>

    <script type="text/javascript">
        

        function deleteData(_this, id) {
            var _this = $(_this);
            Alert.confirm(
                'Delete','Are you sure you want to delete this?',
                'Yes',
                'No',
                function(instance, toast) {
                    System.setAjaxRequest(
                        url.deleteUrl+id,
                        '',
                        'DELETE',
                        function(response) {
                            if (response.status == 200) 
                            {
                                if (response.success) {
                                    _this.closest('tr').fadeOut( function() {
                                        _this.closest('tr').remove();
                                        $('.datatable').DataTable().destroy();
                                        $('.card-body').html(response.content);
                                        $('.datatable').DataTable({responsive:true});
                                    });
                                    Alert.success('Deleted',response.message);
                                } else {
                                    Alert.error('Error',response.message, 'topRight');
                                }
                            }
                        },
                        function() {
                            Alert.error('Error',System.errorTextMessage, 'topRight');
                        }
                    );
                },
                function(instance, toast) {

                }
            )
        }
        
    </script>
@endsection