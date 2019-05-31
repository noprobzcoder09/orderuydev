@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('new-customer'))

@section('content')
<div class="row">
    <div class="col-md-8">
    @include('errors.messages')
    </div>
</div>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                New Customer
            </div>
            <div class="card-body">
                @include($view.'customer.index-form')
            </div>
        </div>
    </div>
    <!--/.col-->
</div>
<!--/.row-->
@endsection


@section('css')

@endsection

@section('script')
<script src="{{asset('vendors/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/validator.js')}}"></script>
<script type="text/javascript">
    
        var url = {
            actionUrl: "{{url($actionUrl)}}",
            verifyEmailUrl: "{{url($verifyEmailUrl)}}",
            editUrl: "{{url($editUrl)}}/"
        }

        var form = '#customer-form';

        $(function (){

            Validator.init(form, {
                rules: {
                    first_name: 'required',
                    last_name: 'required',
                    phone: 'required',
                    address1: 'required',
                    country: 'required',
                    state: 'required',
                    delivery_zone_timings_id: 'required',
                    suburb: 'required',
                    postcode: {
                        required: true,
                        number: true
                    },
                    email: {
                        required: true,
                        email: true,
                        remote: {
                            url: url.verifyEmailUrl,
                            type: "POST",
                            headers: {
                                'X-CSRF-TOKEN' : $('meta[name="meta-csrf"]').attr('content')
                            }
                         }
                    }
                },
                messages: {
                    email: {
                        required: 'Please enter email.',
                        email: 'Please enter a valid email address.',
                        remote: "Email address is already registered."
                    },
                    firstname: {
                        required: 'Please enter a first name.'
                    },
                    lastname: {
                        required: 'Please enter a first name.'
                    },
                    phone: {
                        required: 'Please enter a first name.'
                    },
                    address1: {
                        required: 'Please enter a first name.'
                    },
                    country: {
                        required: 'Please enter a country.'
                    },
                    state: {
                        required: 'Please enter a state.'
                    },
                    postcode: {
                        required: 'Please enter a postal code.',
                        number: 'Please a number only.'
                    },
                    delivery_zone_timings_id: {
                        required: 'Please enter a delivery day.'
                    },
                },
                submitHandler: function () {
                    System.blockUI($(form));
                    System.lazyLoading( function() {
                        System.setAjaxRequest(
                            url.actionUrl,
                            $(form).serialize()+'&state_desc='+$('#state').find('option:selected').text(),
                            'PUT',
                            function(response) {
                                System.unblockUI();
                                if (response.status == 200)
                                {
                                    if (response.success) {
                                        $(form)[0].reset();
                                        System.successMessage(response.message);
                                        System.lazyLoading( function() {
                                            window.location.href = url.editUrl+response.id;
                                        },2000);
                                    } else {
                                        System.errorMessage(response.message);
                                    }
                                } else {
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