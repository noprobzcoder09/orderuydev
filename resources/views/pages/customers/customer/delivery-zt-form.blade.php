@csrf
<input type="hidden" name="id" value="{{isset($id) ? $id : ''}}">
<div class="form-group">
    <label for="delivery_zone_timings_id">Delivery Zone Timing</label>
    <select id="delivery_zone_timings_id" name="delivery_zone_timings_id" class="form-control">
        <option value="">Please select</option>
        <?php $zone = []; $old = ''; ?>
        @foreach($zoneTimingList as $row)
        <?php $old = $row->delivery_zone_id; ?>
        @if(!in_array($row->delivery_zone_id, $zone) || empty($zone))
        <optgroup label="{{$row->zone_name}}">
        @endif
            <option value="{{$row->id}}" {{isset($profile->delivery_zone_timings_id) && $profile->delivery_zone_timings_id == $row->id ? 'selected' : '' }} >{{$row->delivery_day}} Delivery Day, Order By Previous {{$row->cutoff_day. ' '. date('h:iA', strtotime($row->cutoff_time))}}</option>
        @if( $old != $row->delivery_zone_id)
        </optgroup>
        @endif
        <?php $zone[] = $row->delivery_zone_id; ?>
        @endforeach
    </select>
</div>