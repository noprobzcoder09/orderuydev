<form class="form-horizontal" id="meals-plan-form" action="{{url($actionUrl)}}" method="PUT">
    @csrf
    <input type="hidden" name="id" value="{{isset($data->id) ? $data->id : ''}}">
    
    <div class="form-group">
        <label for="sku">Sku</label>
        <input type="text" class="form-control" name="sku" id="sku" placeholder="Enter sku here" value="{{isset($data->sku) ? $data->sku : ''}}">
    </div>

    <div class="form-group">
        <label for="plan_name">Plan</label>
        <input type="text" class="form-control" id="plan_name" name="plan_name" placeholder="Enter plan name here" value="{{isset($data->plan_name) ? $data->plan_name : ''}}">
    </div>

    <div class="form-group">
        <label for="no_meals">Number of Days</label>
        <input type="text" class="form-control" id="no_days" name="no_days" placeholder="Enter no. of days here" value="{{isset($data->no_days) ? $data->no_days : ''}}">
    </div>

    <div class="form-group">
        <label for="no_meals">Number of Meals</label>
        <input type="text" class="form-control" id="no_meals" name="no_meals"placeholder="Enter no. of meals here" value="{{isset($data->no_meals) ? $data->no_meals : ''}}">
    </div>

    <div class="form-group">
        <label class="switch switch-text switch-pill switch-primary">
            <input type="checkbox" class="switch-input" name="vegetarian" id="vegetarian" {{isset($data->vegetarian) && $data->vegetarian ? 'checked' : ''}}>
            <span class="switch-label" data-on="Yes" data-off="No"></span>
            <span class="switch-handle"></span>
        </label>
        <label for="vegetarian">Vegetarian</label>
    </div>

    <div class="form-group">
        <label for="ins_product_id">Infusionsoft  Product</label>
        <select class="form-control select2-single" id="ins_product_id" name="ins_product_id">
            <option value="">Please select</option>
            @foreach($products as $row)
            <option {{isset($data->ins_product_id) && $data->ins_product_id == $row['Id'] ? 'selected' : ''}} value="{{$row['Id']}}">{{$row['ProductName']}}</option>
            @endforeach
           
        </select>
    </div>

    <div class="form-group">
        <label for="price">Price</label>
        <input type="text" class="form-control" id="price" name="price" placeholder="Enter price here" value="{{isset($data->price) ? $data->price : ''}}">
    </div>

    <div class="form-group">
        <label class="" for="file-input">Image</label>
        <input id="meal_plan_image" name="meal_plan_image" type="file">
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