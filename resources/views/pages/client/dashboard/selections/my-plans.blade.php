<div class="row margin-top-20 width-50-none-auto">
    <div class="col-md-12">
        <div class="form-group">
            <label for="subscription">Select Subscription</label>
            <div class="select-style slate">
                <select id="subscription" name="subscription" class="form-control">
                    <option value="">Please select</option>
                    @foreach($myPlans as $row)
                    <option value="{{$row->id}}">{{$row->plan_name.($row->vegetarian ? ' (Vegetarian)' : '')}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>