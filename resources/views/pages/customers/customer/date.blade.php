<div id="date-wrapper" class="margin-bottom-10">
    <div class="row">
        <div class="col-sm-12">
            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                </span>
                <select class="form-control date" name="date" placeholder="Enter Date here">
                    <option value="">Select date</option>
                    @foreach($dates as $date) 
                    <option value="{{$date}}">{{$date}}</option>
                    @endforeach
                </select>
                <span class="input-group-prepend cursor-pointer" onclick="Customer.pause(this, $(this).parent().find('.date').val())">
                    <span class="input-group-text"><i class="fa fa-check"></i></span>
                </span>
                <span class="input-group-prepend cursor-pointer" onclick="Customer.closeInputDate(this)">              
                    <span class="input-group-text"><i class="fa fa-times"></i></span>
                </span>
            </div>
        </div>
    </div>
</div>