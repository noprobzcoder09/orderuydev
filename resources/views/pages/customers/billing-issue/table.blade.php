<table class="table table-hover table-align-middle mb-0 datatable">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Plans</th>
            <th>Total Amount</th>
            <th>Billing Attempts No.</th>
            <th>Weeks Active</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
        <tr>
           <td>{{$row->name}}</td>
           <td>{{$row->email}}</td>
           <td>{{$row->mobile_phone}}</td>
           <td>{!!$row->plan_name!!}</td>
           <td>{{$row->price}}</td>
           <td title="{{$row->billing_attempt_desc}}">{{$row->billing_attempt}}</td>
           <td>{{$row->weeks_active}}</td>
           <td>
                <div class="btn-group">
                    <button class="btn btn-radius-none btn-secondary dropdown-toggle" type="button" id="groupbuttonadvance-'.$row->id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="groupbuttonadvance-'.$row->subscriptions_cycle_id.'">
                        <a href="{{url('customers/edit/'.$row->user_id)}}" class="m-btn dropdown-item">Manage Subscriptions</a>
                        <a href="javascript:;" class="m-btn dropdown-item" onclick="BillingIssue.showCardModal(this, {{$row->user_id}})">Update Card</a>
                        <a href="javascript:;" class="m-btn dropdown-item" onclick="BillingIssue.billNow(this, {{$row->user_id}})">Bill Now</a>
                        <a href="javascript:;" class="m-btn dropdown-item" onclick="BillingIssue.cancelForTheWeek(this, {{$row->user_id}})">Cancel For Week Only</a>
                        <a href="javascript:;" class="m-btn dropdown-item" onclick="BillingIssue.cancelSubscription(this, {{$row->user_id}})">Cancel Subscriptions</a>
                    </div>
                </div>
           </td>
        </tr>
        @endforeach
    </tbody>
</table>