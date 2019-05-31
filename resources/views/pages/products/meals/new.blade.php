@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('product-meals-new'))

@section('content')

<div class="row">
    <div class="col-md-6">
    @include('errors.messages')
    </div>
</div>
<div class="row">
	<div class="col-sm-6">
		<div class="card" id="meal-container">
			<div class="card-header">
				<strong>Meals</strong>
				<small>New</small>
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
        verifySkuUrl: "{{url($verifySkuUrl)}}",
        redirectEditUrl: "{{url($editUrl)}}/",
    }

    var form = '#meals-form';

    $(function (){

        Validator.init(form, {
            rules: {
                meal_sku: {
                    required: true,
                    remote: {
                        url: url.verifySkuUrl,
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN' : $('meta[name="meta-csrf"]').attr('content')
                        }
                     }
                },
                meal_name: {
                    required: true
                }
            },
            messages: {
                meal_sku: {
                    required: 'Please enter a sku.',
                    remote: "SKU is already taken."
                },
                meal_name: {
                    required: 'Please enter a meal name.',
                    remote: "Meal Name is already taken."
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
                                System.lazyLoading( function() {
                                    window.location.href = url.redirectEditUrl+response.id;
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