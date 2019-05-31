@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('users-edit', $data['id']))

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
				<strong>User</strong>
				<small>Edit</small>
			</div>
			<div class="card-body">
				@include($view.'form')
				@include($view.'image')
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
		verifyEmail: "{{url($verifyEmailUrl)}}",		
		redirectUrl: "{{url($masterlistUrl)}}",
	}

	var form = '#user-form';

	$(function (){

		Validator.init(form, {
			rules: {
				name: {
					required: true,
					minlength: 2
				},
				email: {
					required: true,
					email: true
				},
				role: 'required'
			},
			messages: {
				email: {
					required: 'Please enter a valid email address',
					minlength: 'Your username must consist of at least 2 characters',
					remote: "Email address is already exist."
				},
				name: {
					required: 'Please enter name',
					minlength: 'Name must consist of at least 2 characters'
				},
			},
			submitHandler: function () {
				System.setAjaxRequest(
					url.actionUrl,
					$(form).serialize(),
					'PATCH',
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