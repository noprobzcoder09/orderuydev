<div class="row margin-top-20">
    <div class="col-md-12">
        <form id="billing-form">

            <div class="newline text-center"><h3>Billing Address</h3></div>
            <div class="newline">&nbsp;</div>
            <input type="hidden" name="country" id="country" value="Australia">
            <div class="row">
                <div class="form-group col-sm-6">
                    <input type="text" value="{{isset($details->billing_first_name) ? $details->billing_first_name : ''}}" class="form-control" name="first_name" id="first_name" placeholder="First Name">
                </div>   
                <div class="form-group col-sm-6">
                    <input type="text" value="{{isset($details->billing_last_name) ? $details->billing_last_name : ''}}"  class="form-control" name="last_name" id="last_name" placeholder="Last Name">
                </div>              
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="text" value="{{isset($details->billing_mobile_phone) ? $details->billing_mobile_phone : ''}}" class="form-control" name="mobile_phone" id="mobile_phone" placeholder="Phone Number" onkeypress="return isNumberKey(event)" maxlength="15">
                    </div>   
                    <div class="form-group">
                        <input type="text" value="{{isset($account->email) ? $account->email : ''}}" class="form-control" name="email" id="email" placeholder="Email Address" readonly/>
                    </div>              
                </div> 
            </div>

            

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
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control" value="{{isset($address->suburb) ? $address->suburb : ''}}" name="suburb" id="suburb" placeholder="Suburb">
                    </div>   
                </div> 
                <div class="form-group col-sm-12">
                    <div class="select-style slate">
                        <select id="state" name="state" class="form-control icon-sm">
                            <option value="">State</option>
                            @foreach(\App\Models\Country::find(1)->state()->get() as $row)
                            <option {{ isset($address->state) ? ($address->state == $row->id ? 'selected' : '' ) : '' }} value="{{$row->id}}">{{$row->state}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>   
            
            </div>
            <div class="row">
                <div class="form-group col-sm-12">
                    <input type="text" class="form-control" value="{{isset($address->postcode) ? $address->postcode : ''}}" name="postcode" id="postcode" placeholder="Postal Code">
                </div>  
            </div>
            
            <div class="row margin-top-20">
                <div class="col-md-12">
                    @include($view.'billing.save-info-address-btn')
                </div>
            </div>
        </form>
    </div>
</div>
