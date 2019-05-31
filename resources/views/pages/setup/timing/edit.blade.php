@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('delivery-timing-edit', $data['id']))

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
				<strong>Delivery Schedule</strong>
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

@endsection

@section('script')
<script src="{{asset('vendors/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/validator.js')}}"></script>
<script type="text/javascript">

	var url = {
		actionUrl: "{{url($actionUrl)}}",
		redirectUrl: "{{url($masterlistUrl)}}",
	}

	var form = '#zone-form';

	$(function (){

		Validator.init(form, {
			rules: {
				delivery_day: {
					required: true
				},
				cutoff_day: {
					required: true
				},
				cutofftime_hour: {
					required: true
				},
				cutofftime_minute: {
					required: true
				},
				cutofftime_a: {
					required: true
				}
			},
			messages: {
				delivery_day: {
					required: 'Please select a delivery day.'
				},
				cutoff_day: {
					required: 'Please select a cutoff day.'
				},
				cutofftime_hour: {
					required: 'Please select a cutoff hour.'
				},
				cutofftime_minute: {
					required: 'Please select a cutoff minute.'
				},
				cutofftime_a: {
					required: 'Please select a cutoff am/apm.'
				}
			},
			submitHandler: function () {
				System.setAjaxRequest(
					url.actionUrl,
					$(form).serialize(),
					'PATCH',
					function(response) {
						if (response.success) {
							$(form)[0].reset();
							System.successMessage(response.message);
							System.lazyLoading( function() {
								window.location.href = url.redirectUrl;
							});
						} else {
							System.errorMessage(response.message);
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