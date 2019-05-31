@extends('layouts.app')
@section('css')
<link href="{{asset('vendors/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection

@section('breadcrumbs', Breadcrumbs::render('find-email'))

@section('script')
  	<script type="text/javascript">
        var url = {
            url: "{{url($url)}}",
            edit: "{{url($url)}}/edit/",
            search: "{{url($search)}}",
        }

        var searchResult = $('#search-result');

        $(document).ready( function() {
            $('#email-checker-form').submit( function() {
                findEmail($('#email').val());
                return false;
            });

            $(document).keydown(function(e) {
                if (e.keyCode == 13) {
                    $('#email-checker-form').trigger('submit');
                }
            });
        });

        function showError(message) {
            searchResult
                    .html(message)
                    .removeClass('text-info')
                    .addClass('text-danger')
                        .fadeIn();
        }

        function showSuccesss(message) {
            searchResult
                    .html(message)
                    .removeClass('text-danger')
                    .addClass('text-info')
                        .fadeIn();
        }

        function findEmail(email) {
            if (!isFormValid('#email-checker-form', false)) {
                showError('Please enter email address.');
                return;
            }

            System.setAjaxRequest(
                url.search,
                {email: email},
                'POST',
                function(response) {
                    if (response.status == 200) 
                    {
                        window.location.href = response.url;
                    }
                },
                function(error) {
                    console.log(error);
                },
                'json'
            );
        }

  	</script>
@endsection

@section('content')

<div class="m-content">

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="m-portlet m-portlet--responsive-tablet-and-mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="flaticon-email"></i>
                            </span>
                            <h3 class="m-portlet__head-text m--font-brand">
                                Email Checker
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="m-portlet__body card-body">
                    @include($view.'customer.email-checker-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection