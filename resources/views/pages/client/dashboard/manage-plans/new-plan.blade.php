<div id="delivery-wrapper" class="width-50">
    <div class="newline">&nbsp;</div>
    <form id="new-plan-form">
        <div class="row">
            <div class="form-group col-sm-12">
                <div class="select-style slate">
                    <select onchange="createNewPlan()" id="meal_plans_id" name="meal_plans_id" class="form-control icon-sm">
                        <option value="">Add New Plan</option>
                        @foreach((new \App\Repository\ProductPlanRepository)->whereNotIn($myPlansIdOnly) as $row)
                        <option  value="{{$row->id}}">{!!$row->plan_name. ($row->vegetarian ? ' (<sup>Vegetarian</sup>)' : '')!!}</option>
                        @endforeach
                    </select>
                </div>
            </div>   
        </div>
    </form>
</div>