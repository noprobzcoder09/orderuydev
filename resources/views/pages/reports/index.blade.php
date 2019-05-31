@extends('layouts.app')

@section('css')
<link href="{{asset('vendors/css/daterangepicker.min.css')}}" rel="stylesheet">
@endsection

@section('breadcrumbs', Breadcrumbs::render('reports'))

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i>
            </div>
            <div class="card-body">
                @include($view.'form')
            </div>
        </div>
    </div>
    <!--/.col-->
</div>
<!--/.row-->
@endsection

@section('script')
<script src="{{asset('vendors/js/moment.min.js')}}"></script>
<script src="{{asset('vendors/js/daterangepicker.min.js')}}"></script>
<script src="{{asset('vendors/js/jquery.validate.min.js')}}"></script>

<script type="text/javascript">

    $(document).ready( function() {
        Load.init();

        Inputs.parameters.change( function() {
            Inputs.bindDaterange();
        });

        $('#timings').change(function() {
            
            let timing_id = $(this).val();

            // Do the historic selection retrieval
            $.get('{{url("reports/get-historic-cycles")}}/' + timing_id, function(data) {
                if (data.length > 0) {
                    for (var i = 0; i >= 0; i++) {
                        elem = '<option value="' + data[i]['id'] + '">' + data[i]['formatted']['delivery_date'] +'</option>';
                        $('#parameters').append(elem);
                    }
                }
            });

        });

    });

    var Request = {
        save: function() {
            System.blockUI(Inputs.form);
            System.lazyLoading( function() {
                var d = new Date();
                window.location.href = Request.url.generate+'?'+Inputs.get()+'&d='+d.getTime();
                System.unblockUI();
            });
        },
        timings: function(location) {
            System.lazyLoading( function() {
                System.setAjaxRequest(
                    Request.url.timings+location,
                    '',
                    "GET",
                    function(response) {
                        Inputs.populateTimings(response);
                    }
                );
            });
        },
        url: {
            generate: "{{url($generateUrl)}}",
            timings: "{{url($timingsUrl)}}/"
        }
    };

    var $validator;
    var Load = {
        init: function() {

            this.validator();

            Inputs.form.submit( function() {
                if (Inputs.isValid()) {
                    Request.save();
                }
                return false;
            });
        },
        validator: function() {
            $validator = Inputs.form.validate({
                rules: {
                    reports: 'required',
                    parameters: 'required',
                    daterange: 'required',
                    location: 'required',
                    timings: 'required'
                 }
            });
        },
        daterange: function() {
            Inputs.daterange.daterangepicker();
        }
    };

    var Parameters = {
        isDaterange: function () {
            if (Inputs.parameters.find('option:selected').val().toLowerCase() == 'date range') {
                return true;
            }
            return false;
        }
    };

    var Inputs = {
        form: $('#report-form'),
        daterange: $('#daterange'),
        location: $('#location'),
        parameters: $('#parameters'),
        timings: $('#timings'),
        get: function() {
            return this.form.serialize()
        },
        getLocation: function() {
            return this.location.find('option:selected').val();
        },
        showDaterange: function() {
            this.daterange.parent().fadeIn();
        },
        hideDaterange: function() {
            this.daterange.parent().fadeOut();
        },
        init: function() {
            this.hideDaterange();
            Load.daterange();
        },
        applyFilter: function() {
            if (Parameters.isDaterange()) {
                this.daterange.val('');
            }
        },
        populateTimings: function(data) {
            var options = '<option value="all">All</option>';
            for (var i in data) {
                options += '<option value="'+data[i].id+'">Cutoff '+data[i].cutoff_day+' for '+data[i].delivery_day+' delivery</option>';
            }
            this.timings.html(options);
        },
        isValid: function () {
            var valid = this.form.valid();
            if(!valid) {
                $validator.focusInvalid();
                return false;
            }
            return true;
        },
        bindDaterange: function() {
            if (Parameters.isDaterange()) {
                this.showDaterange();
            } else {
                this.hideDaterange();
            }
        }
    };
   
</script>
@endsection