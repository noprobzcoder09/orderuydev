<form id="for-delivery-form">
	<div class="row">
		<div class="form-group col-sm-6">
			<label for="delivery_timings_id_{{$subscriptionId}}">For Delivery On</label>
			<span id="delivery_timings_{{$subscriptionId}}">{{$deliveryDate ?? 'None'}}</span>
		</div>  
	</div>  
</form>