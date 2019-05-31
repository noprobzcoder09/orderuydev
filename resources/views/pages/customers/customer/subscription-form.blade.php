<div class="row">
    <div class="col-md-5">
<form class="horizontl-form" id="subscription-form">
    @csrf
    <input type="hidden" name="id" value="{{isset($id) ? $id : ''}}">
    <div class="form-group">
        <label for="meal_plans_id">Meal Plan</label>
        <select onchange="ManagePlan.updatePlan()" id="meal_plans_id" name="meal_plans_id" class="form-control">
            <option value="">Please select</option>
            @foreach($mealPlans as $row)
            <option value="{{$row->id}}">{{$row->plan_name. ' '.__('config.currency').number_format($row->price, 2)}}</option>
            @endforeach
        </select>
    </div>
    <div class="row delivery-timing-container">
        <div class="form-group col-sm-12">
            <label for="manageplan_delivery_zone_id">Location</label>
            <div class="select-style slate">
                <select id="manageplan_delivery_zone_id" onchange="ManagePlan.deliveryTimings()" name="delivery_zone_id" class="form-control icon-sm">
                    <option value="">Select Pickup location</option>
                    @foreach((new App\Repository\ZTRepository)->getActiveLocations() as $row)
                    <option {{ $delivery_zone_id == $row->id ? 'selected' : ''  }} value="{{$row->id}}">{{$row->zone_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>   
        <div class="form-group col-sm-12">
            <label for="manageplan_delivery_zone_timings_id">Delivery Timing</label>
            <div class="select-style slate">
                <select id="manageplan_delivery_zone_timings_id" name="delivery_zone_timings_id" class="form-control icon-sm">
                    <option value="">Choose Delivery Time</option>                                
                </select>
            </div>
        </div>       
    </div>
    <div class="form-group">
        <label for="coupons">Coupons</label>
        <select id="coupons" name="coupons" multiple class="form-control select2-single" style="width: 100% !important;">
            @foreach($coupons as $row)
            <option value="{{$row->id}}">{{$row->coupon_code}}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="cards"><i class="fa fa-spinner cursor-pointer" onclick="loadCards(this, true)"></i> Select Credit Cards</label>
        <select name="card_id"  class="form-control card_id" style="width: 100% !important;">
            <option value="">Please select</option>
        </select>
    </div>
</form>
    </div>

    <div class="col-md-7" id="subscription-order-summary">
    </div>
</div>