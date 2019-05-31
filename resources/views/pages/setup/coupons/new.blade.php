@extends('layouts.app')


@section('breadcrumbs', Breadcrumbs::render('coupons-new'))


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
				<strong>Coupons</strong>
				<small>New</small>
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
		verifyNameUrl: "{{url($verifyNameUrl)}}",
	}

	var form = '#coupons-form';

	$(document).ready( function() {
		$('#products, #user').select2({theme: "bootstrap"});

		var date = new Date();
		date.setDate(date.getDate()-1);
		$('#expiry_date').datepicker({
			startDate: date,
			autoclose: true
		});
	});

	$(function (){

		Validator.init(form, {
			rules: {
				coupon_code: {
					required: true
				},
				discount_type: {
					required: true
				},
				discount_value: {
					required: true
				},
				min_order: {
					number: true
				},
				max_uses: {
					required: true,
					number: true
				},
				expiry_date: {
					required: true
				}
			},
			messages: {
		
			},
			submitHandler: function () {
				var users = JSON.stringify($('#user').val());
				var products = JSON.stringify($('#products').val());
				
				System.setAjaxRequest(
					url.actionUrl,
					$(form).serialize()+'&users='+users+'&products_sel='+products,
					'PUT',
					function(response) {
						if (response.status == 200)
						{
							if (response.success) {
								$(form)[0].reset();
								$('#products, #user').val(null).trigger('change');
								System.successMessage(response.message);
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