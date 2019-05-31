<form id="meta-form" class="form-horizontal" action="{{url($actionUrl)}}" method="PUT">
	@csrf
    <input type="hidden" name="id" value="">
    <input type="hidden" name="meal_id" value="{{isset($data->id) ? $data->id : ''}}">
	<div class="form-group checkbox-creat-new-wrapper">
		<div class="form-check checkbox">
	      <input class="form-check-input" id="create_new" name="create_new" type="checkbox">
	      <label class="form-check-label" for="create_new">
	        Create New
	      </label>
	    </div>
	</div>

	<div class="form-group field-search-wrapper">
		<label for="search_field">Search Meta Key</label>
		<select id="search_field" name="search_field" class="form-control select2-single">
			<option></option>
		</select>
	</div>

	<div class="form-group field-input-wrapper">
		<label for="field">Field</label>
		<input type="text" class="form-control" id="meta_key" name="meta_key" placeholder="Enter field here">
	</div>

	<div class="form-group field-input-wrapper">
		<label for="company">Value</label>
		<input type="text" class="form-control" id="meta_value" name="meta_value" placeholder="Enter value here">
	</div>

	<div class="row">
        <div class="col-sm-6 text-left">
            
        </div>
        <div class="col-sm-6 text-right">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> Submit</button>
        </div>
    </div>
</form>