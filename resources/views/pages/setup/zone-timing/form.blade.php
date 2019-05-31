<form class="form-horizontal" id="zone-form" action="{{url($actionUrl)}}" method="PUT">
    @csrf
    <input type="hidden" name="id" value="{{isset($data->id) ? $data->id : ''}}">
    <div class="form-group">
        <label for="delivery_zone_id">Delivery Zone</label>
        <select id="delivery_zone_id" name="delivery_zone_id" class="form-control">
            <option value="">Please select</option>
            @foreach($zones as $row)
            <option {{ isset($data->delivery_zone_id) && $data->delivery_zone_id == $row->id ? 'selected' : '' }} value="{{$row->id}}">{{$row->zone_name}}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="delivery_timings_id">Delivery Schedule</label>
        <select id="delivery_timings_id" name="delivery_timings_id" class="form-control">
            <option value="">Please select</option>
            @foreach($timings as $row)
            <option {{ isset($data->delivery_timings_id) && $data->delivery_timings_id == $row->id ? 'selected' : '' }} value="{{$row->id}}">{{$row->delivery_day.' / Cutoff Day '.$row->cutoff_day.' at '.date('h:i A',strtotime($row->cutoff_time))}}</option>
            @endforeach
        </select>
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