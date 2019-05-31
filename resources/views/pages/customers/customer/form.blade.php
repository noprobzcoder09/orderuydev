@csrf
<input type="hidden" name="id" value="{{isset($account->id) ? $account->id : ''}}">
<input type="hidden" name="country" id="country" value="Australia">
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" value="{{isset($profile->first_name) ? $profile->first_name : ''}}" class="form-control" id="first_name" name="first_name" placeholder="Enter first name here">
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" value="{{isset($profile->last_name) ? $profile->last_name : ''}}" class="form-control" id="last_name" name="last_name" placeholder="Enter last name here">
        </div>
    </div>
</div>
<!--/.row-->
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" readonly="" value="{{$edit ? (isset($account->email) ? $account->email : '') : Request::get('email')}}" class="form-control" id="email" name="email" placeholder="Enter email here">
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group">
            <label for="mobile_phone">Phone</label>
            <input type="text" value="{{isset($profile->mobile_phone) ? $profile->mobile_phone : ''}}" class="form-control" id="mobile_phone" name="mobile_phone" placeholder="Enter phone here" onkeypress="return isNumberKey(event)" maxlength="15">
        </div>
    </div>
</div>
<!--/.row-->
<div class="row">
    <div class="col-sm-12">
        <h3>Billing Address</h3>
    </div>
</div>
<!--/.row-->
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="address1">Address 1</label>
            <input type="text" value="{{isset($address->address1) ? $address->address1 : ''}}" class="form-control" id="address1" name="address1" placeholder="Enter address 1 here">
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group">
            <label for="address2">Address 2</label>
            <input type="text" value="{{isset($address->address2) ? $address->address2 : ''}}" class="form-control" id="address2" name="address2" placeholder="Enter address 2 here">
        </div>
    </div>
</div>
<!--/.row-->

<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label for="address1">Suburb</label>
            <input type="text" class="form-control" value="{{isset($address->suburb) ? $address->suburb : ''}}" name="suburb" id="suburb" placeholder="Suburb">
        </div>   
    </div> 

    <div class="col-sm-4">
        <div class="form-group">
            <label for="state">State</label>
            <select class="form-control" id="state" name="state">
                <option value="">--Choose--</option>
                @foreach(\App\Models\Country::find(1)->state()->get() as $row)
                <option {{ isset($address->state) ? ($address->state == $row->id ? 'selected' : '' ) : '' }} value="{{$row->id}}">{{$row->state}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group">
            <label for="address1">Postal Code</label>
            <input type="text" value="{{isset($address->postcode) ? $address->postcode : ''}}" class="form-control" id="postcode" name="postcode" placeholder="Enter postal code here">
        </div>
    </div>
</div>
