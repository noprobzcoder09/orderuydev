	<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="FusedSoftware">
    <meta name="keyword" content=",">
    <meta name="meta-csrf" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="img/favicon.png">
    <title>FusedSoftware</title>

    <!-- Icons -->
    <link href="{{ asset('vendors/css/flag-icon.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/css/simple-line-icons.min.css') }}" rel="stylesheet">

    <!-- Main styles for this application -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- Custom styles for this application -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

    @yield('css')

    <style type="text/css">
    .margin-bottom-10 {
        margin-bottom: 10px;
    }
    .text-indent-20 {
        text-indent: 20px;
    }
    .margin-left-10 {
        margin-left: 10px;
    }
</style>

</head>
<body class="app flex-row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
	        <div class="clearfix">
	          <h1 class="float-left display-3 mr-4">200</h1>
	          <h4 class="pt-3">Token Renewal has been successful.</h4>
	          <p class="text-muted">You can now redirect to your page.</p>
	        </div>
	        <div class="input-prepend input-group">
	            <a href="{{url('/redirectusers')}}" class="btn btn-info" type="button">Go to your page.</a>
	        </div>
	      </div>
        </div>
    </div>

    <!-- Bootstrap and necessary plugins -->
    <script src="{{asset('vendors/js/jquery.min.js')}}"></script>
    <script src="{{asset('vendors/js/popper.min.js')}}"></script>
    <script src="{{asset('vendors/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('vendors/js/pace.min.js')}}"></script> 
    <script src="{{asset('vendors/js/blockui.js')}}"></script> 
    

    <!-- CoreUI Pro main scripts -->
    <script src="{{asset('js/global.js')}}"></script>   
    <script src="{{asset('js/system.js')}}"></script>   
    <script src="{{asset('js/alert.js')}}"></script>    

    <script type="text/javascript">
        
       

    </script>
</body>
</html>

