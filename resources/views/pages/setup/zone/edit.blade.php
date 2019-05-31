@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('delivery-zone-edit', $data['id']))

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
				<strong>Delivery Zone</strong>
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
				System.blockUI($(form));
				System.lazyLoading( function() {
					System.setAjaxRequest(
						url.actionUrl,
						$(form).serialize(),
						'PATCH',
						function(response) {
							System.unblockUI();
							if (response.success) {
								$(form)[0].reset();
								System.successMessage(response.message);
								System.lazyLoading( function() {
									window.location.href = url.redirectUrl;
								});
							} else {
								console.log(response.message);
								System.errorMessage(response.message);
							}
						},
						function(error) {
							System.unblockUI();
							System.errorMessage();
						}
					);
				});
				return false;
			}
		});
	});

</script>
@endsection