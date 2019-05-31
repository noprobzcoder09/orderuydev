<div id="delivery-wrapper" class="width-50">
    <div class="newline">&nbsp;</div>
    <form id="delivery-form">
        <input type="hidden" name="current_delivery_zone_timings_id" id="current_delivery_zone_timings_id" value="{{isset($details->delivery_zone_timings_id) ? $details->delivery_zone_timings_id : ''}}">
        <div class="row">
            <div class="form-group col-sm-6">
                <div class="select-style slate">
                    <select id="delivery_zone_id" name="delivery_zone_id" class="form-control icon-sm">
                        <option value="">Select Pickup location</option>
                        @foreach((new App\Repository\ZTRepository)->getActiveLocations() as $row)
                        @php
                            $delivery_address = $row->delivery_address !== '' && $row->delivery_address !== null ? ' - '.$row->delivery_address : '';
                        @endphp
                        <option {{ isset($details->delivery_zone_id) ? ($details->delivery_zone_id == $row->id ? 'selected' : '' ) : '' }} value="{{$row->id}}">{{$row->zone_name}}{{$delivery_address}}</option>
                        @endforeach
                    </select>
                </div>
            </div>   
            <div class="form-group col-sm-6">
                <div class="select-style slate">
                    <select id="delivery_zone_timings_id" name="delivery_zone_timings_id" class="form-control icon-sm">
                        <option value="">Choose Delivery Time</option>                                
                    </select>
                </div>
            </div>       
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <textarea class="form-control" name="delivery_notes" id="delivery_notes" placeholder="Notes about your order (Optional)">{{isset($details->delivery_notes) ? $details->delivery_notes : ''}}</textarea> 
                </div>   
            </div> 
        </div>
        <div class="row margin-top-20">
            <div class="col-md-12">
                @include($view.'delivery.update-btn')
            </div>
        </div>  
    </form>
</div>