@csrf
<form class="horizontl-form" id="subscription-form">
    <input type="hidden" name="id" value="{{isset($id) ? $id : ''}}">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                 <div class="col-sm-12">
                    <span class="bold" style="color: #00692b">&nbsp;</span></h4>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-12">
                    <label for="meal_plans_id">Meal Plan</label>
                    <div class="select-style slate">
                        <select onchange="ManagePlan.updatePlan()" id="meal_plans_id" name="meal_plans_id" class="form-control">
                            <option value="">Please select</option>
                          
                            @foreach((new \App\Repository\ProductPlanRepository)->getAvailableMealPlans() as $row)
                            <option data-sku="{{$row->sku}}" value="{{$row->id}}">{{$row->plan_name. ' x1 '.__('config.currency').number_format($row->price, 2)}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row delivery-timing-container">
                <div class="form-group col-sm-6">
                    <label for="manageplan_delivery_zone_id">Location {{$details->delivery_zone_id}}</label>
                    <div class="select-style slate">
                        <select id="manageplan_delivery_zone_id" onchange="ManagePlan.deliveryTimings()" name="delivery_zone_id" class="form-control icon-sm">
                            <option value="">Select Pickup location</option>
                            @foreach((new App\Repository\ZTRepository)->getActiveLocations() as $row)
                            @php
                                $delivery_address = $row->delivery_address !== '' && $row->delivery_address !== null ? ' - '.$row->delivery_address : '';
                            @endphp
                            <option {{ isset($details->delivery_zone_id) ? ($details->delivery_zone_id == $row->id ? 'selected' : '' ) : '' }} value="{{$row->id}}">{{$row->zone_name}}{{$delivery_address}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>   
                <div class="form-group col-sm-6">
                    <label for="manageplan_delivery_zone_timings_id">Delivery Timing</label>
                    <div class="select-style slate">
                        <select id="manageplan_delivery_zone_timings_id" name="delivery_zone_timings_id" class="form-control icon-sm">
                            <option value="">Choose Delivery Time</option>                                
                        </select>
                    </div>
                </div>       
            </div>
            <div class="row coupon-container">
                <div class="col-md-12">
                    <div class="form-group">
                        <div id="coupon-link-wrapper" class="text-center">
                            <a href="javascript:;" onclick="ManagePlan.createCoupon();">Add Coupon</a>
                        </div>
                        <div id="coupon-input-wrapper" style="display: none;">
                            <div class="input-group">
                                <input placeholder="Coupon / Promo Code" class="form-control remove-border-shadow" id="coupon_code" name="coupon_code" type="text">
                                <span class="input-group-prepend cursor-pointer" onclick="ManagePlan.storeCoupon()">
                                    <span class="input-group-text"><i class="fa fa-check"></i></span>
                                </span>
                                <span class="input-group-prepend cursor-pointer" onclick="ManagePlan.closeNewCoupon()">
                                    <span class="input-group-text"><i class="fa fa-times"></i></span>
                                </span>
                            </div>
                            <em id="coupon_error" style="color: red; font-size: 12px;display: none;">Error Invalid</em>
                        </div>
                    </div>
                </div>
            </div>  
        </div>
        <div class="col-md-12" id="subscription-order-summary">
            
        </div>
    </div>
    
</form>
