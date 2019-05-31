@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('coupons-edit', $data['id']))

@section('content')

<div class="row">
    <div class="col-md-6">
    @include('errors.messages')
    </div>
</div>
<div class="row">
	<div class="col-sm-6">
		<div class="card">
			<div class="card-header">
				<strong>Coupon</strong>
				<small>Edit</small>
			</div>
			<div class="card-body">
				@include($view.'form')
			</div>
		</div>
	</div>
<!--/.col-->
</div>

@endsection


@section('css')
<link href="{{asset('vendors/css/select2.min.css')}}" rel="stylesheet">
<link href="{{asset('vendors/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
@endsection

@section('script')
<script src="{{asset('vendors/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/validator.js')}}"></script>
<script src="{{asset('vendors/js/select2.min.js')}}"></script>
<script src="{{asset('vendors/js/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript">

	var url = {
		actionUrl: "{{url($actionUrl)}}",
		redirectUrl: "{{url($masterlistUrl)}}",
	}

	var form = '#coupons-form';

	$(document).ready( function() {
		$('#products, #user').select2({theme: "bootstrap"});
		$('#expiry_date').datepicker({format: 'yyyy-mm-dd',})
	
		
	});

	$(function (){

		Validator.init(form, {
			rules: {
				zone_name: {
					required: true
				}
			},
			messages: {
				zone_name: {
					required: 'Please enter a name.',
					minlength: 'Name must consist of at least 2 characters.',
					remote: "Name is already taken."
				}
			},
			submitHandler: function () {
				var users = JSON.stringify($('#user').val());
				var products = JSON.stringify($('#products').val());
				System.setAjaxRequest(
					url.actionUrl,
					$(form).serialize()+'&users='+users+'&products_sel='+products,
					'PATCH',
					function(response) {
						if (response.status == 200)
						{
							if (response.success) {
								$(form)[0].reset();
								System.successMessage(response.message);
								System.lazyLoading( function() {
									window.location.href = url.redirectUrl;
								});
							} else {
								System.errorMessage(response.message);
							}
						}
					},
					function(error) {
						System.errorMessage();
					}
				);
				return false;
			}
		});
	});
</script>
@endsection