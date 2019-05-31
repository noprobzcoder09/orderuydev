@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('coupons-all'))

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
                                Coupons Masterlist
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

@endsection


@section('css')
    <link rel="stylesheet" href="{{ asset('/template/custom/datatables/datatables.bundle.css') }}">
@endsection

@section('script')
    <script src="{{ asset('/template/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(function(){
          $('.datatable').DataTable({responsive:true});
          // $('.datatable').css({'border-collapse':'collapse !important'});
          $('.datatable').attr('style', 'border-collapse: collapse !important');
        });

    </script>

    <script type="text/javascript">
            
        var url = {
            deleteUrl: "{{url($deleteUrl)}}/"
        }

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