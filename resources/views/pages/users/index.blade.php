@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('users-all'))

@section('css')
    <link rel="stylesheet" href="{{ asset('/template/custom/datatables/datatables.bundle.css') }}">
@endsection

@section('script')
    <script src="{{ asset('/template/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
    <script src="{{asset('js/views/customer/admin/customer.js')}}"></script>

  	<script type="text/javascript">
        var url = {
            resetPasswordUrl: "{{url($resetPasswordUrl)}}/",
        }

  		$(function(){
            $('.datatable').DataTable({responsive:true});
            // $('.datatable').css({'border-collapse':'collapse !important'});
            $('.datatable').attr('style', 'border-collapse: collapse !important');
		});

  	</script>
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
                </div>

                <div class="m-portlet__body card-body">
                  @include($view.'tables')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection