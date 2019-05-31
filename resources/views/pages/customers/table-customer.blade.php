<?php
$first_name = isset($profile->first_name) ? $profile->first_name : 'None';
$last_name = isset($profile->last_name) ? $profile->last_name : 'None';
$address1 = isset($address->address1) ? $address->address1 : 'None';
$address2 = isset($address->address2) ? $address->address2 : 'None';
$email = isset($account->email) ? $account->email : 'None';
$mobile_phone = isset($profile->mobile_phone) ? $profile->mobile_phone : 'None';
$name = $first_name.' '. $last_name;

?>

<!-- <h3>Profile</h3> -->
<div>
<!--     <a href="javascript:;" title="Edit Customer" onclick="editCustomer()">
    	<i class="fa fa-edit fa-lg mt-4"></i>
	</a> -->
	<strong>{{$name}}</strong>
</div>
<div>{{is_null($address1) || $address1 == 'None' ? '' : $address1}}</div>
<div>{{is_null($address2) || $address2 == 'None' ? '' : $address2}}</div>
<div>Email: {{$email}}</div>
<div>Phone: {{$mobile_phone}}</div>
