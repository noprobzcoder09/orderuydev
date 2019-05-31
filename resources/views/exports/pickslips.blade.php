<table>
    <tbody>
    <tr>
        <td colspan="4">{{$locationName}}</td>
    </tr>
  	@foreach($customers as $row)
  	<tr>
  		<td>Customer: {{$row->first_name.' '.$row->last_name}}</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
  	</tr>
    <tr>
        <td>Package: {{$row->plan_name}}</td>
        <td>&nbsp;</td>
        <td>For Delivery On: {{date('l dS F Y', strtotime($row->delivery_date))}}</td>
        <td>&nbsp;</td>
    </tr>
    <?php $mealTotal = 0; ?>
    @foreach($meals[$row->user_id][$row->subscription_id] as $key => $meal)
    <tr>
        <td style="border: 1px solid #000;">{{$key}}</td>
        <td>{!!$meal->name!!}</td>
        <td>&nbsp;</td>
        <td>{{$meal->quantity}}</td>
    </tr>
    <?php $mealTotal += $meal->quantity; ?>
  	@endforeach
    <tr>
        <td>Packed By: </td>
        <td>&nbsp;</td>
        <td>Item Total:</td>
        <td>{{$mealTotal}}</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    @endforeach
    </tbody>
</table>
