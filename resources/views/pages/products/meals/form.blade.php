<form class="form-horizontal" id="meals-form" action="{{url($actionUrl)}}" method="PUT">
    @csrf
    <input type="hidden" name="id" value="{{isset($data->id) ? $data->id : ''}}">
    
    <div class="form-group">
        <label for="company">Sku</label>
        <input type="text" class="form-control" name="meal_sku" id="meal_sku" placeholder="Enter sku here" value="{{isset($data->meal_sku) ? $data->meal_sku : ''}}">
    </div>

    <div class="form-group">
        <label for="company">Meal</label>
        <input type="text" class="form-control" name="meal_name" id="meal_name" placeholder="Enter meal name here" value="{{isset($data->meal_name) ? $data->meal_name : ''}}">
    </div>

    <div class="form-group">
        <label class="switch switch-text switch-pill switch-primary">
            <input type="checkbox" class="switch-input" name="vegetarian" {{isset($data->vegetarian) && $data->vegetarian == 1 ? 'checked' : ''}}>
            <span class="switch-label" data-on="Yes" data-off="No"></span>
            <span class="switch-handle"></span>
        </label>
        <label for="vegetarian">&nbsp;Vegetarian</label>
    </div>
    
    <div class="row">
        <div class="col-sm-6 text-left">
            
        </div>
        <div class="col-sm-6 text-right">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> Submit</button>
        </div>
    </div>
</form>