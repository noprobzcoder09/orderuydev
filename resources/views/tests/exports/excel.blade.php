
<table>
    <thead>
    <tr>
        <th><b>User</b></th>
        <th><b>Meal Plan</b></th>
        <th><b>Cycle</b></th>
        <th><b>Subscription Id</b></th>
        <th><b>Cycle Subscription Status</b></th>
        <th><b>Invoice Id</b></th>
    </tr>
    </thead>
    <tbody>
  	@foreach($subscriptions as $row)
  	<tr>
  		<td>{{$row->user_id}}</td>
  		<td>
          {{$row->subscriptions->meal_plan_id}}
        </td>
  		<td>{{$row->cycle_id}}</td>
  		<td>{{$row->subscriptions->id}}</td>
        <td>{{$row->cycle_subscription_status}}</td>
        <td>{{$row->ins_invoice_id}}</td>
  	</tr>
  	@endforeach
    </tbody>
</table>