<div class="row margin-top-20">
    <div class="col-md-12">
        <form id="profile-form">

            <div class="newline text-center"><h3>Profile</h3></div>
            <div class="newline">&nbsp;</div>

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
                        <input type="text" value="{{isset($details->mobile_phone) ? $details->mobile_phone : ''}}" class="form-control" name="mobile_phone" onkeypress="return isNumberKey(event)" maxlength="15" id="mobile_phone" placeholder="Phone Number">
                    </div>   
                    <div class="form-group">
                        <input type="text" value="{{isset($account->email) ? $account->email : ''}}" class="form-control" name="email" id="email" placeholder="Email Address" readonly />
                    </div>              
                </div> 
            </div>

        </form>
    </div>

    <div class="col-md-12">
        <form id="profile-password">

            <div class="newline text-center"><h3>Account Password</h3></div>
            <div class="newline">&nbsp;</div>

            <div class="row">
                <div class="form-group col-sm-12">
                    <input type="password" value="" class="form-control" name="current_password" id="current_password" placeholder="Current Password">
                </div>   
            </div>

            <div class="row">
                <div class="form-group col-sm-12">
                    <input type="password" value="" class="form-control" name="password" id="password" placeholder="New Password">
                </div>   
            </div>

            <div class="row">
                <div class="form-group col-sm-12">
                    <input type="password" value="" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm Password">
                </div>   
            </div>
        </form>
    </div>


    <div class="col-md-12 margin-top-20">
        @include($view.'profile.update-profile-btn')
    </div>

</div>
