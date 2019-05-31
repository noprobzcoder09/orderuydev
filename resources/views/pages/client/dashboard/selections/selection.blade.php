<div class="row">
    <div class="col-md-12">
        <h2>
            <span class="bold" style="color: #00692b;text-transform: uppercase;"> 
            {{$plan}} 
            </span>
        </h2>
        <label for="delivery_timings_id_{{$subscriptionId}}">For Delivery On</label>
        <span id="delivery_timings_{{$subscriptionId}}">{{$deliveryDate ?? 'None'}}</span>
        <form class="menu-form" id="menu-form-{{$subscriptionId}}" data-id="{{$subscriptionId}}" data-subcycleid="{{$subscriptionCycleId}}">
            @if(count($lunch) > 0)
            <h3 class="sub-title">LUNCH</h3>
            
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    @for($i = 0; $i < $noDays; $i++)
                    <div class="form-group">
                        <div class="select-style slate">
                            <select id="option_{{$i}}" name="option_{{$i}}" class="form-control meals-selection lunch-meal-select">
                                <option value="">--Choose --</option>
                                @foreach($meals as $row)
                                <option {{ !empty($lunch) && (array_key_exists($i, $lunch) && $lunch[$i] == $row->id)  ? 'selected' : '' }} value="{{$row->id}}">{{$row->meal_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endfor
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-center">
                    <img class="selection-meal-image" src="{{$image}}" width="60%">
                </div>
            </div>
            @endif
            @if(count($dinner) > 0)
            <h3 class="sub-title">DINNER</h3>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    @for($i = 0; $i < $noDays; $i++)
                    <div class="form-group">
                        <div class="select-style slate">
                            <select id="option_dinner_{{($i)}}" name="option_dinner_{{$i}}" class="form-control  meals-selection dinner-meal-select">
                                <option value="">--Choose --</option>
                                @foreach($meals as $row)
                                <option {{ !empty($dinner) && (array_key_exists($i, $dinner) && $dinner[$i] == $row->id)  ? 'selected' : '' }} value="{{$row->id}}">{{$row->meal_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endfor
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-center">
                    <img class="selection-meal-image" src="{{$image}}" width="60%">
                </div>
            </div>
            @endif
           
        </form>
    </div>
</div>