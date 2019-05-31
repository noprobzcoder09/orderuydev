<div id="date-wrapper">
    <div id="date-container">
        <div class="input-group">
            <span class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
            </span>
            <select class="form-control date" name="date" placeholder="Enter Date here" required >
                <option>Select date</option>
                @foreach($dates as $date) 
                <option value="{{$date}}">{{date('l jS F Y', strtotime($date))}}</option>
                @endforeach
            </select>
            <span class="input-group-prepend cursor-pointer" onclick="singleSaveStopTillDate(this, {{$subscriptionId}}, {{$subscriptionCycleId}}, $(this).closest('div').find('.date').val(), '{{$subscriptionCycleStatus}}', '{{$deliveryDate}}')">
                <span class="input-group-text"><i class="fa fa-check"></i></span>
            </span>
            <span class="input-group-prepend cursor-pointer" onclick="closeStopTillDate(this)">              
                <span class="input-group-text"><i class="fa fa-times"></i></span>
            </span>
        </div>
    </div>
</div>