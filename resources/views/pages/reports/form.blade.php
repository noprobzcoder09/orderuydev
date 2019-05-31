<form class="form-horizontal" id="report-form" action="" method="PUT">
    @csrf
   
    <div class="form-group">
        <label for="timings">Timings</label>
        <select id="timings" name="timings" class="form-control">
            <option value="">Please select</option>
            @foreach($timings as $row)
            <option value="{{$row->id}}">{{$row->delivery_day}} Delivery, Order By Previous {{$row->cutoff_day.' '.date('h:iA', strtotime($row->cutoff_time))}}</option>
            @endforeach 
            </select>
        </select>
    </div>

    <div class="form-group">
        <label for="parameters">Reports Range</label>
        <select id="parameters" name="parameters" class="form-control">
            @foreach($parameters as $key => $caption)
            <option>{{$caption}}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group row">
        <div class="col-md-9 col-form-label">
            <div class="form-check form-check-inline mr-1">
                <input class="form-check-input" type="radio" id="complete-option" checked value="complete" name="export_type">
                <label class="form-check-label" for="complete-option">Complete Reports</label>
            </div>
            <div class="form-check form-check-inline mr-1">
                <input class="form-check-input" type="radio" id="kitchen-only-option" value="kitchen-only" name="export_type">
                <label class="form-check-label" for="kitchen-only-option">Kitchen Reports</label>
            </div>
        </div>
    </div>

    <div class="form-group" style="display: none;">
        <label for="daterange">Date Range</label>
        <input type="text" class="form-control" name="daterange" id="daterange">
    </div>



    <div class="row">
        <div class="col-sm-12 text-right">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> Submit</button>
        </div>
    </div>
</form>
