<form class="form-horizontal" id="zone-form" action="{{url($actionUrl)}}" method="PUT">
	@csrf
	<input type="hidden" name="id" value="{{isset($data->id) ? $data->id : ''}}">
	<div class="form-group">
	    <label for="company">Zone</label>
	    <input type="text" class="form-control" name="zone_name" id="zone_name" placeholder="Enter zone here" value="{{isset($data->zone_name) ? $data->zone_name : ''}}">
	</div>

	<div class="form-group">
	    <label for="company">Delivery Address</label>
	    <input type="text" class="form-control" name="delivery_address" id="delivery_address" placeholder="Enter delivery address here" value="{{isset($data->delivery_address) ? $data->delivery_address : ''}}">
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