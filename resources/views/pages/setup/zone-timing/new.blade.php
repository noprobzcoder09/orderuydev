@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('delivery-zone-timing-new'))

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
				<strong>Delivery Zone Schedule</strong>
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

@endsection

@section('script')
<script src="{{asset('vendors/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/validator.js')}}"></script>
<script type="text/javascript">

	var url = {
		actionUrl: "{{url($actionUrl)}}"
	}

	var form = '#zone-form';

	$(function (){

		Validator.init(form, {
			rules: {
				delivery_zone_id: {
					required: true
				},
				delivery_timings_id: {
					required: true
				}
			},
			messages: {
				delivery_zone_id: {
					required: 'Please enter a zone.'
				},
				delivery_timings_id: {
					required: 'Please enter a timing.'
				}
			},
			submitHandler: function () {
				System.setAjaxRequest(
					url.actionUrl,
					$(form).serialize(),
					'PUT',
					function(response) {
						if (response.status == 200)
						{
							if (response.success) {
								$(form)[0].reset();
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