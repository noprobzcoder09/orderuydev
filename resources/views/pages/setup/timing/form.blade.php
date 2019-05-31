<?php
	$day = [
		'Monday',
		'Tuesday',
		'Wednesday',
		'Thursday',
		'Friday',
		'Saturday',
		'Sunday'
	];
	$time = '';
	$hour = '';
	$minute = '';
	$ampm = '';
	if ($edit) {
		$time = explode(' ',date('h:i A', strtotime($data->cutoff_time)));
		$hour = explode(':',$time[0])[0];
		$minute = explode(':',$time[0])[1];
		$ampm = $time[1];
	}
?>
<form class="form-horizontal" id="zone-form" action="{{url($actionUrl)}}" method="PUT">
	@csrf
	<input type="hidden" name="id" value="{{isset($data->id) ? $data->id : ''}}">
	
	<div class="form-group">
	    <label for="delivery_day">Delivery Day</label>
	    <select id="delivery_day" name="delivery_day" class="form-control">
	        <option value="">Please select</option>
	        <option {{isset($data->delivery_day) && $data->delivery_day == $day[0] ? 'selected' : '' }} value="Monday">Monday</option>
	        <option {{isset($data->delivery_day) && $data->delivery_day == $day[1] ? 'selected' : '' }} value="Tuesday">Tuesday</option>
	        <option {{isset($data->delivery_day) && $data->delivery_day == $day[2] ? 'selected' : '' }} value="Wednesday">Wednesday</option>
	        <option {{isset($data->delivery_day) && $data->delivery_day == $day[3] ? 'selected' : '' }} value="Thursday">Thursday</option>
	        <option {{isset($data->delivery_day) && $data->delivery_day == $day[4] ? 'selected' : '' }} value="Friday">Friday</option>
	        <option {{isset($data->delivery_day) && $data->delivery_day == $day[5] ? 'selected' : '' }} value="Saturday">Saturday</option>
	        <option {{isset($data->delivery_day) && $data->delivery_day == $day[6] ? 'selected' : '' }} value="Sunday">Sunday</option>
	    </select>
	</div>

	<div class="form-group">
	    <label for="cutoff_day">Cutoff Day</label>
	    <select id="cutoff_day" name="cutoff_day" class="form-control">
	        <option value="">Please select</option>
	        <option {{isset($data->cutoff_day) && $data->cutoff_day == $day[0] ? 'selected' : '' }} value="Monday">Monday</option>
	        <option {{isset($data->cutoff_day) && $data->cutoff_day == $day[1] ? 'selected' : '' }} value="Tuesday">Tuesday</option>
	        <option {{isset($data->cutoff_day) && $data->cutoff_day == $day[2] ? 'selected' : '' }} value="Wednesday">Wednesday</option>
	        <option {{isset($data->cutoff_day) && $data->cutoff_day == $day[3] ? 'selected' : '' }} value="Thursday">Thursday</option>
	        <option {{isset($data->cutoff_day) && $data->cutoff_day == $day[4] ? 'selected' : '' }} value="Friday">Friday</option>
	        <option {{isset($data->cutoff_day) && $data->cutoff_day == $day[5] ? 'selected' : '' }} value="Saturday">Saturday</option>
	        <option {{isset($data->cutoff_day) && $data->cutoff_day == $day[6] ? 'selected' : '' }} value="Sunday">Sunday</option>
	    </select>
	</div>

	<div class="form-group">
	    <label for="company">Time</label>
	    <div class="row">
	    	<div class="col-md-4" style="padding-right: 0 !important">
			    <select id="cutofftime_hour" name="cutofftime_hour" class="form-control">
			        <option value="">Hour</option>
			        @for($i = 1; $i <= 12; $i++)
			        <?php $i = $i < 10 ? '0'.$i : $i;?>
			        <option {{$hour == $i ? 'selected' : '' }} value="{{$i}}">{{$i}}</option>
			        @endfor
			    </select>
			</div>
			<div class="col-md-4" style="padding-left: 0 !important;padding-right: 0 !important;">
			    <select id="cutofftime_minute" name="cutofftime_minute" class="form-control">
			        <option value="">Minute</option>
			        @for($i = 1; $i <= 59; $i++)
			        <?php $i = $i < 10 ? '0'.$i : $i;?>
			        <option {{$minute == $i ? 'selected' : '' }} value="{{$i}}">{{$i}}</option>
			        @endfor
			    </select>
			</div>
			<div class="col-md-4" style="padding-left: 0 !important">
			    <select id="cutofftime_" name="cutofftime_a" class="form-control">
			        <option value="">AM/PM</option>
			        <option {{ strtolower($ampm) == 'am' ? 'selected' : '' }} value="am">AM</option>
			        <option {{ strtolower($ampm) == 'pm' ? 'selected' : '' }} value="pm">PM</option>
			    </select>
			</div>
		</div>
	</div>

	<div class="row">
	    <div class="col-sm-6 text-left">
	        @if($edit)
	        <a href="{{url($masterlistUrl)}}"> Back</a>
	        @endif
	    </div>
	    <div class="col-sm-6 text-right">
	        <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> Submit</button>
	    </div>
	</div>
</form>