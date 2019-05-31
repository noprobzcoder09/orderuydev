<div class="row">
    <div class="col-md-5">
        <form name="addnewmenuprevweek-form" id="addnewmenuprevweek-form">
            @csrf
            <input type="hidden" name="user_id" value="{{ $userId ?? 0 }}">
            <input type="hidden" name="subscriptions_id" value="{{ $subscriptionId ?? 0 }}">
            <input type="hidden" name="delivery_zone_id" value="">
            <input type="hidden" value="{{$previousMealPlan->meal_plans_id}}" id="prev_week_meal_plans_id" name="meal_plans_id">
            
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="state">Select Previous Delivery Week</label>
                        <select class="form-control" id="cycle_id" name="cycle_id" onchange="PreviousWeekMenu.cycleState()">
                            <option value="">--Choose--</option>
                            @foreach($previous_cycle as $row)
                            <option selected value="{{$row->id}}" data-delivery_zone_id="{{$row->delivery_zone_id}}">{{$row->delivery_date}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <?php /*
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="state">Select a Plan</label>
                        <select class="form-control" onchange="PreviousWeekMenu.updatePlan()" id="prev_week_meal_plans_id" name="meal_plans_id">
                            <option value="">--Choose--</option>
                            @foreach($plans as $row)
                                <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                */?>
                
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="cards"><i class="fa fa-spinner cursor-pointer" onclick="loadCards(this, true)"></i> Select Credit Cards</label>
                        <select name="card_id"  class="form-control card_id" style="width: 100% !important;">
                            <option value="">Please select</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-7" id="addnewmenuprevweek-subscription-order-summary">
    </div>
</div>