<div class="row">
    <div class="col-md-12">
    @include('errors.messages',['id' => 'checkout-message'])
    </div>
</div>
<div class="row margin-top-20">
    <div class="col-md-12">
        <form id="shipping-form">

            <h1 class="text-center"><span class="bold" style="color: #00692b">YOUR DETAILS</span></h1>
            <div class="newline">&nbsp;</div>
            <input type="hidden" name="country" id="country" value="Australia">
            <div class="row">
                <div class="form-group col-sm-6">
                    <input type="text" value="{{isset($details->first_name) ? $details->first_name : ''}}" class="form-control" name="first_name" id="first_name" placeholder="First Name">
                </div>   
                <div class="form-group col-sm-6">
                    <input type="text" value="{{isset($details->last_name) ? $details->last_name : ''}}"  class="form-control" name="last_name" id="last_name" placeholder="Last Name">
                </div>              
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="text" value="{{isset($details->mobile_phone) ? $details->mobile_phone : ''}}" class="form-control" name="mobile_phone" id="mobile_phone" placeholder="Phone Number">
                    </div>   
                    <div class="form-group">
                        <input type="text" value="{{isset($account->email) ? $account->email : ''}}" class="form-control" name="email" id="email" placeholder="Email Address">
                    </div>              
                </div> 
            </div>

            <div class="newline"><h3>Billing Address</h3></div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control" value="{{isset($address->address1) ? $address->address1 : ''}}" name="address1" id="address1" placeholder="Address Line 1">
                    </div>   
                    <div class="form-group">
                        <input type="text" class="form-control" value="{{isset($address->address2) ? $address->address2 : ''}}" name="address2" id="address2" placeholder="Address Line 2">
                    </div>              
                </div> 
            </div>

             <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <input type="text" class="form-control" value="{{isset($address->suburb) ? $address->suburb : ''}}" name="suburb" id="suburb" placeholder="Suburb">
                    </div>   
                </div> 
               
                <div class="form-group col-sm-4">
                    <div class="select-style slate">
                        <select id="state" name="state" class="form-control icon-sm">
                            <option value="">State</option>
                            @foreach(\App\Models\Country::find(1)->state()->get() as $row)
                            <option {{ isset($address->state) ? ($address->state == $row->id ? 'selected' : '' ) : '' }} value="{{$row->id}}">{{$row->state}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>   

                 <div class="form-group col-sm-4">
                    <input type="text" class="form-control" value="{{isset($address->postcode) ? $address->postcode : ''}}" name="postcode" id="postcode" placeholder="Postal Code">
                </div>  
            </div>
           
            <div id="delivery-wrapper" class="width-70">
                <h1 class="text-center"><span class="bold" style="color: #00692b">DELIVERY</span></h1>
                <div class="newline">&nbsp;</div>
                
                <div class="row">
                    <div class="form-group col-sm-6">
                        <div class="select-style slate">
                            <select id="delivery_zone_id" name="delivery_zone_id" class="form-control icon-sm">
                                <option value="">Select Pickup location</option>
                                @foreach(\App\Models\DeliveryZone::get() as $row)
                                <option  value="{{$row->id}}">{{$row->zone_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>   
                    <div class="form-group col-sm-6">
                        <div class="select-style slate">
                            <select id="delivery_zone_timings_id" name="delivery_zone_timings_id" class="form-control icon-sm">
                                <option value="">Choose Delivery Time</option>                                
                            </select>
                        </div>
                    </div>           
                </div>
            </div>
            <div class="newline margin-top-20">&nbsp;</div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <textarea class="form-control" name="delivery_notes" id="delivery_notes" placeholder="Notes about your order (Optional)">{{isset($details->delivery_notes) ? $details->delivery_notes : ''}}</textarea> 
                    </div>   
                </div> 
            </div>
             
        </form>
    </div>
</div>