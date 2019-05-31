<div class="row">
    <div class="col-md-12">
        
        <form class="menu-form">
            @if(!empty($user_menu_selections['lunch']) && count($user_menu_selections['lunch']) > 0)
            <h3 class="sub-title">LUNCH</h3>
            
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    @for($i = 0; $i < $no_of_days; $i++)
                    <div class="form-group">
                        <div class="select-style slate">
                            <select id="option_{{$i}}" name="menus[]" class="form-control meals-selection lunch-meal-select">
                                <option value="">--Choose --</option>
                                @foreach($default_meals as $row)
                                <option {{ !empty($user_menu_selections['lunch']) && (array_key_exists($i, $user_menu_selections['lunch']) && $user_menu_selections['lunch'][$i] == $row->id)  ? 'selected' : '' }} value="{{$row->id}}">{{$row->meal_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endfor
                </div>
               
            </div>
            @endif
            @if(!empty($user_menu_selections['dinner']) && count($user_menu_selections['dinner']) > 0)
            <h3 class="sub-title">DINNER</h3>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    @for($i = 0; $i < $no_of_days; $i++)
                    <div class="form-group">
                        <div class="select-style slate">
                            <select id="option_dinner_{{($i)}}" name="menus[]" class="form-control  meals-selection dinner-meal-select">
                                <option value="">--Choose --</option>
                                @foreach($default_meals as $row)
                                <option {{ !empty($user_menu_selections['dinner']) && (array_key_exists($i, $user_menu_selections['dinner']) && $user_menu_selections['dinner'][$i] == $row->id)  ? 'selected' : '' }} value="{{$row->id}}">{{$row->meal_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endfor
                </div>
               
            </div>
            @endif

            <div class="action">
                <button type="button" class="btn btn-success btn-sm" onclick="PreviousSelections.update(this.form, {{$subscription_cycle_id}})">Update</button>
            </div>
        </form>
    </div>
</div>