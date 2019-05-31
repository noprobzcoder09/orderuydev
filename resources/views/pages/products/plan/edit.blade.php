@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('product-plans-edit', $data['id']))

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
				<strong>Meals Plan</strong>
				<small>Edit</small>
			</div>
			<div class="card-body">
				@include($view.'form')
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		@include($view.'image')
	</div>
<!--/.col-->
</div>

@endsection


@section('css')
<link href="{{asset('vendors/css/select2.min.css')}}" rel="stylesheet">
@endsection

@section('script')
 <script src="{{asset('vendors/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/validator.js')}}"></script>
<script src="{{asset('vendors/js/select2.min.js')}}"></script>
<script type="text/javascript">

	var url = {
		actionUrl: "{{url($actionUrl)}}",
		redirectUrl: "{{url($masterlistUrl)}}",
	}

	var form = '#meals-plan-form';

	$(document).ready( function() {
		$('#ins_product_id').select2({theme: "bootstrap"});
	});

	$(function (){

		Validator.init(form, {
			rules: {
				sku: {
					required: true
				},
				plan_name: {
					required: true
				},
				no_meals: {
					required: true,
					number: true
				},
				stripe_product_id: {
					required: true,
					number: true
				},
				price: {
					required: true,
					number: true
				}
			},
			messages: {
				sku: {
					required: 'Please enter a sku.'
				},
				plan_name: {
					required: 'Please enter a plan name.'
				},
				no_meals: {
					required: 'Please enter a number of meals.',
					number: 'Please enter a number only.',
				},
				stripe_product_id: {
					required: 'Please enter a product.',
					number: 'Please enter a number only.',
				},
				price: {
					required: 'Please enter a price.',
					number: 'Please enter a number only.',
				}
			},
			submitHandler: function () {

				var formData = new FormData();

				$(form).find('input[type="text"], input[type="hidden"]').each( function() {
					formData.append($(this).attr('name'),$(this).val());
				});

				$(form).find('select').each( function() {
					formData.append($(this).attr('name'),$(this).val());
				});

				$(form).find('input[type="checkbox"]').each( function() {
					formData.append($(this).attr('name'),$(this).is(':checked'));
				});

				$(form).find('input[type="file"]').each( function() {
					if ($(this)[0].files[0] != undefined) {
						formData.append($(this).attr('name'),$(this)[0].files[0]);
					}
				});

				System.setAjaxFile(
					url.actionUrl,
					formData,
					'POST',
					function(response) {
						if (response.status == 200)
						{
							if (response.success) {
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