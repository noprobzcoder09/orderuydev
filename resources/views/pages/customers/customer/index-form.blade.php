<form class="horizontl-form" id="customer-form">
	<h3>Customer Profile</h3>
	<hr>
	@include($view.'customer.form')
	<h3>Delivery</h3>
	<hr>
	@include($view.'customer.delivery-zt-form')

	<div class="row">
	    <div class="col-sm-6 text-left">
	        @if($edit)
	        <a href="{{url($masterlistUrl)}}"> Back</a>
	        @endif
	    </div>
	    <div class="col-sm-6 text-right">
	        <button type="submit" id="submit-form" class="btn btn-lg btn-primary"><i class="fa fa-dot-circle-o"></i> Submit</button>
	    </div>
	</div>
</form>