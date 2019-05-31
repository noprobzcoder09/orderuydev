@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('users-new'))

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
				<strong>Users</strong>
				<small>Change Password</small>
			</div>
			<div class="card-body">
				@include($view.'password.form')
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
		verifyEmail: "{{url($verifyEmailUrl)}}"
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
					email: true,
					remote: {
	                    url: url.verifyEmail,
	                    type: "POST",
	                    headers: {
				    		'X-CSRF-TOKEN' : $('meta[name="meta-csrf"]').attr('content')
				    	}
	                 }
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