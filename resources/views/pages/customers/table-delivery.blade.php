<?php
$zone_name = isset($zoneTiming->zone_name) ? $zoneTiming->zone_name : 'None';
$cutoff_day = isset($zoneTiming->cutoff_day) ? $zoneTiming->cutoff_day : 'None';
$cutoff_time = isset($zoneTiming->cutoff_time) ? $zoneTiming->cutoff_time : 'None';
$delivery_day = isset($zoneTiming->delivery_day) ? $zoneTiming->delivery_day : 'None';

?>
<!-- <h3>Delivery Zone Timing</h3> -->
<div>
<!-- 	<a href="javascript:;" title="Edit Customer" onclick="editDeliveryZoneTiming(this)">
        <i class="fa fa-edit fa-lg mt-4"></i>
    </a> -->
	<strong>Delivery Zone:</strong> {{$zone_name}}</div>
<div><strong>Delivery Schedule:</strong> {{$delivery_day}} Delivery, Order By Previous {{$cutoff_day}} {{date('h:ia', strtotime($cutoff_time))}} </div>
<!--/.col-->