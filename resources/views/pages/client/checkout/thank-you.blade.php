@extends('layouts.client')

@section('content')
<div class="row">
	<div class="col-ms-12">
		 @include($view.'my-orders')
	</div>
</div>
@endsection

@section('css')
<link href="{{ asset('css/client.css') }}" rel="stylesheet">

<style type="text/css">
	#checkout-message {
		text-align: left !important;
	}
	ol > li {
		float: left;
		padding-right: : 5px;
		list-style: none;
	}
	ol > li::after {
		content: ",";
	}

	ol > li:last-child::after {
		content: "";
	}
</style>
@endsection

@section('script')


  	<script type="text/javascript">
  		
  	</script>
@endsection

@section('content')

@endsection