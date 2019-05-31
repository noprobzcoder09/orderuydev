<form id="delivery-form">
    <div class="row">
        <div class="form-group col-sm-6">
            <div class="select-style slate">
                <select id="delivery_zone_id" name="delivery_zone_id" class="form-control icon-sm">
                    <option value="">Select Pickup location</option>
                    @foreach(\App\Models\DeliveryZone::where('disabled',0)->get() as $row)
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
    <div class="row margin-top-20">
        <div class="col-md-12 text-center">
            <a href="javascript:;" onclick="Action.updateDeliveryZone()" class="btn btn-lg btn-ecommerce">Save</a>
        </div>
    </div>  
</form>