@if (count($unpaidSubscriptions) > 0)
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info">
            <p>
                Due to a billing issue, at the moment you will NOT be receiving your delivery of the following items.
            </p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table">
            <tbody>
                <tr>
                    <td style="border-top: 0 none !important" colspan="2" class="text-center">For Delivery on {{$unpaidSubscriptions[0]->delivery_date ?? 'None'}}</td>
                </tr>
                @foreach($unpaidSubscriptions as $row)
                <tr>
                    <td style="font-weight: bold;">{{$row->plan_name}}</td>
                    <td width="3%">
                        <button onclick="Action.cancelSubscriptionCycle(this, {{$row->subscription_id}}, {{$row->subscriptions_cycle_id}})" type="button" class="btn btn-sm" style="background-color: #fff !important"><i class="fa text-danger fa-times" style="font-size: 20px;"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@if(count($forDeliverySubscriptions) > 0)
<div class="row">
    <div class="col-md-12">
        <p>
            Note, you have paid for and will be receiving:
        </p>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table">
            <tbody>
                @foreach($forDeliverySubscriptions as $row)
                <tr>
                    <td>{{$row->plan_name}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
<div class="row">
    <div class="col-md-12">
        <p>
            In order to receive these items, please select from one of the following options:
        </p>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <button onclick="Action.chargeCard()" type="button" class="btn btn-lg btn-primary"><i class="fa fa-check"></i> Charge My Stored Card</button>
    </div>
    <div class="col-md-6">
        <button onclick="$(Element.cardModal).modal('show');" type="button" class="btn btn-lg btn-success"><i class="fa fa-credit-card"></i> Update My Card Details</button>
    </div>
</div>
<div class="row margin-top-20">
    <div class="col-md-12">
        <p>
            If you no longer wish to receive these items, click the X next to items you no longer want in the list at the top.
        </p>
    </div>
</div>


@else
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info">
            <p>
                There is no billing issue.
            </p>
        </div>
    </div>
</div>
@endif