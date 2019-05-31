<div class="row margin-top-20">
    <div class="col-md-12">
        <h2>
            <span class="bold" style="color: #00692b;text-transform: uppercase;"> 
            {{$plan->plan_name}} 
            </span>
        </h2>

        <form id="menu-form">
            <div class="newline margin-top-20">&nbsp;</div>
            @if(count($meals['lunch']) > 0)
            <h3 class="sub-title">LUNCH</h3>
            
            <div class="row">
                <div class="col-md-6">
                    @for($i = 0; $i < $meals['noDays']; $i++)
                    <div class="form-group">
                        <div class="select-style slate">
                            <select id="option_lunch_{{$i}}" name="option_lunch_{{$i}}" class="form-control meal-select lunch-meal-select">
                                <option value="">--Choose --</option>
                                @foreach($meals['lunch'] as $row)
                                <option {{ isset($meals['default']['lunch'][$i]) && $meals['default']['lunch'][$i] == $row->id ? 'selected' : ''  }} value="{{$row->id}}">{{$row->meal_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endfor
                </div>
                <div class="col-md-6 text-center">
                    <img src="{{asset('images/sample1.jpg')}}" width="60%">
                </div>
            </div>
            @endif
            @if(count($meals['dinner']) > 0)
            @php($meals['isDinnerOnly'] ? $i = 0 : $i=$i)
            <div class="newline">&nbsp;</div>
            <h3 class="sub-title">DINNER</h3>
            <div class="row">
                <div class="col-md-6">
                    @for($i = 0; $i < $meals['noDays']; $i++)
                    <div class="form-group">
                        <div class="select-style slate">
                            <select id="option_dinner_{{($i)}}" name="option_dinner_{{$i}}" class="form-control meal-select dinner-meal-select">
                                <option value="">--Choose --</option>
                                @foreach($meals['dinner'] as $row)
                                <option {{ isset($meals['default']['dinner'][$i]) && $meals['default']['dinner'][$i] == $row->id ? 'selected' : ''  }} value="{{$row->id}}">{{$row->meal_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endfor
                </div>
                <div class="col-md-6 text-center">
                    <img src="{{asset('images/sample2.jpg')}}" width="60%">
                </div>
            </div>
            @endif
            <div class="row margin-top-10">
                <div class="col-md-12">
                    <span class="total-price"><b>${{number_format($meals['price'], 2)}}</b></span><span class="price-title">/week</span>
                    <div class="newline" style="margin-top: 0px !important;">
                        <p class="ecommerce-sub-desc">*Never Fear - Additional Meal Selection Can Be Made After Checkout</p>
                    </div>
                </div>
            </div>
            <div class="row margin-top-20">
                <div class="col-md-12">
                    <a href="javascript:;" class="btn btn-lg btn-continue btn-ecommerce btn-addtocart next">Add to cart</a>
                </div>
            </div>
        </form>
    </div>
</div>