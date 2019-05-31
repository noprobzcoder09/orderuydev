<table>
    <thead>
    <tr>
        <th><b>Name</b></th>
        <th><b>Email</b></th>
        <th><b>Phone</b></th>
        <th><b>Meal Plan</b></th>
        <th><b>Notes</b></th>
        <th><b>Customer ID</b></th>
        <th><b>Customer Delivery Location</b></th>
    </tr>
    </thead>
    <tbody>
  	@foreach($customers as $row)
  	<tr>
  		<td>{{$row->first_name.' '.$row->last_name}}</td>
  		<td>{{$row->email}}</td>
  		<td>{{$row->mobile_phone}}</td>
  		<td>{{ implode(',',$customerPlans[$row->user_id] ?? []) }}</td>
      <td>{{$row->delivery_notes}}</td>
      <td>{{$row->user_id}}</td>
      <td>
        {{ $row->zone_name}} - {{ $row->delivery_address }}
       </td>
  	</tr>
  	@endforeach
    </tbody>
</table>