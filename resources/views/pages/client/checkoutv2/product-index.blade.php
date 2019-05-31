@extends('layouts.client')

@section('page-title', '- Order')

@section('content')
@include($view.'selection')
@endsection

@section('css')
<link href="{{ asset('css/client.css') }}" rel="stylesheet">

<style type="text/css">
#checkout-message {
    text-align: left !important;
}

@media only screen and (min-width: 200px) and (max-width: 384px){ 
    
}

@media only screen and (min-width: 385px) and (max-width: 479px){ 
    
}

@media only screen and (min-width: 480px) and (max-width: 767px){ 
  
}

@media only screen and (min-width: 768px) and (max-width: 991px){
    
}

@media only screen and (min-width: 992px) and (max-width: 1999px){
    .container {
        width: 900px;
    }
}

@media only screen and (min-width: 1024px) and (min-height: 768px){
    .container {
        width: 900px;
    }
}

</style>

@endsection

@section('script')

<script src="{{asset('vendors/js/jquery.maskedinput.min.js')}}"></script>
<script src="{{asset('vendors/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/validator.js')}}"></script>
<script src="{{asset('js/views/checkoutv2/menu.js')}}"></script>

<script type="text/javascript">

    var url = {
        addtoCartUrl: "{{url($addtoCartUrl)}}"
    }

    var ajaxResponse = false, passwordVisible = false;

    $(document).ready(function() {

        // Activate selections
        Menu.init({
            addToCartUrl: url.addtoCartUrl,
            noMeals: "{{$meals['noMeals']}}"
        });

        $(document).on('click','.btn-addtocart', function() {
            if (!Menu.isValid()) {
                return false;
            }
            Menu.addtocart("{{$id}}");
        });

    });

</script>
@endsection

@section('content')

@endsection