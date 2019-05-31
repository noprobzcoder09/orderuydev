<form class="form-horizontal" id="coupons-form" action="{{url($actionUrl)}}" method="PUT">
	@csrf
	<input type="hidden" name="id" value="{{isset($data->id) ? $data->id : ''}}">
	<div class="form-group">
	    <label for="company">Coupon Code</label>
	    <input type="text" class="form-control" name="coupon_code" id="coupon_code" placeholder="Enter Coupon Code here" value="{{isset($data->coupon_code) ? $data->coupon_code : ''}}">
	</div>
	<div class="row">
		<div class="form-group col-sm-6">
		    <label for="company">Discount Type</label>
	        <select class="form-control" name="discount_type" id="discount_type">
	        <option value="">Enter Discount Type here</option>
	        <option value="Fixed" {{ isset($data->discount_type) && $data->discount_type == "Fixed" ? 'selected' : '' }}>Fixed</option>
	        <option value="Percent" {{ isset($data->discount_type) && $data->discount_type == "Percent" ? 'selected' : '' }}>Percent</option>
	        </select>
		</div>
		<div class="form-group col-sm-6">
		    <label for="company">Discount Value</label>
		    <input type="text" class="form-control" name="discount_value" id="discount_value" placeholder="Enter Discount Value here" value="{{isset($data->discount_value) ? $data->discount_value : ''}}">
		</div>
	</div>
	<div class="row">
		<div class="form-group col-sm-6">
		    <label for="company">Min Order</label>
		    <input type="text" class="form-control" name="min_order" id="min_order" placeholder="Enter Min Order here" value="{{isset($data->min_order) ? $data->min_order : ''}}">
		</div>
		<div class="form-group col-sm-6">
		    <label for="company">Max Uses</label>
		    <input type="text" class="form-control" name="max_uses" id="max_uses" placeholder="Enter Max Uses here" value="{{isset($data->max_uses) ? $data->max_uses : ''}}">
		</div>
	</div>
	<div class="form-group">
	    <label for="company">Products</label>
	    <select class="form-control" name="products" id="products" multiple>
	    	@foreach(\App\Models\MealPlans::get() as $row)
	    	<?php
	    		$products = [];
	    		if (isset($data->products)) {
	    			$products = json_decode($data->products);
	    			$products = !is_array($products) ? [$products] : $products;
	    		}
	    	?>
	    	<option {{ isset($data->products) ?  in_array($row->id, $products) ? 'selected' : '' : '' }} value="{{$row->id}}">{{$row->plan_name.($row->vegetarian ? '(V)' : '')}}</option>
	    	@endforeach
	    </select>
	</div>
	<div class="form-group">
	    <label for="company">User</label>
	    <select class="form-control" name="user" id="user" multiple>
	    	@foreach(\App\Models\UserDetails::get() as $row)
	    	<option {{ isset($data->user) ? in_array($row->id, json_decode($data->user)) ? 'selected' : '' : '' }} value="{{$row->id}}">{{$row->first_name}}</option>
	    	@endforeach
	    </select>
	</div>
	<div class="form-group">
	    <label for="company">Expiry Date</label>
	    <input type="text" class="form-control" name="expiry_date" id="expiry_date" placeholder="Enter Expiry Date here" value="{{isset($data->expiry_date) ? $data->expiry_date : ''}}">
	</div>

	<div class="form-group">
        <label class="switch switch-text switch-pill switch-primary">
            <input type="checkbox" class="switch-input" name="solo" id="solo" {{isset($data->solo) && $data->solo ? 'checked' : ''}}>
            <span class="switch-label" data-on="Yes" data-off="No"></span>
            <span class="switch-handle"></span>
        </label>
        <label for="vegetarian">Solo</label>
    </div>

    <div class="form-group">
        <label class="switch switch-text switch-pill switch-primary">
            <input type="checkbox" class="switch-input" name="onetime" id="onetime" {{isset($data->onetime) && $data->onetime ? 'checked' : ''}}>
            <span class="switch-label" data-on="Yes" data-off="No"></span>
            <span class="switch-handle"></span>
        </label>
        <label for="vegetarian">Onetime use</label>
    </div>

    <div class="form-group">
        <label class="switch switch-text switch-pill switch-primary">
            <input type="checkbox" class="switch-input" name="recur" id="recur" {{isset($data->recur) && $data->recur ? 'checked' : ''}}>
            <span class="switch-label" data-on="Yes" data-off="No"></span>
            <span class="switch-handle"></span>
        </label>
        <label for="vegetarian">Recurring</label>
    </div>

	<div class="row">
	    <div class="col-sm-6 text-left">
	        @if($edit)
	        <a href="{{url($masterlistUrl)}}"> Back</a>
	        @endif
	    </div>
	    <div class="col-sm-6 text-right">
	        <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> Submit</button>
	    </div>
	</div>
</form>