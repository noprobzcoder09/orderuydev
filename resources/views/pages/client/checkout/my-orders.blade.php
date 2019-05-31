 <h1>Thank You!</h1>
 	
<p>Please check your email for your invoice and login details.</p>
<p><strong>Order ID: <u>{{$orderId}}</u></strong> </p>
<p><strong>Order Date: <u>{{$orderDate}}</u></strong></p>
<hr>
<table class="">
	<tbody>
		@foreach($orders as $order)
		@php(is_array($order) ? $order = (object)$order : $order)
		<tr>
			<td>
				{{$order->plan}}
				<table class="text-indent">
					<tbody>
						@if(count($order->lunch) > 0)
						<tr>
							<td>Lunch</td>
						</tr>
						<tr>
							<td>
								<ol>
									@foreach($order->meals as $row)
										@if(in_array($row->id, $order->lunch))
											<li>{{$row->meal_name}}</li>
										@endif
									@endforeach
								</ol>
							</td>
						</tr>
						@endif
						@if(count($order->dinner) > 0)
						<tr>
							<td>Dinner</td>
						</tr>
						<tr>
							<td>
								<ol>
									@foreach($order->meals as $row)
										@if(in_array($row->id, $order->dinner))
											<li>{{$row->meal_name}}</li>
										@endif
									@endforeach
								</ol>
							</td>
						</tr>
						@endif
					</tbody>
				</table>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
<div class="newline margin-top-20">&nbsp;</div>
<h5>Order: <u>{{__('config.currency').number_format($price, 2)}}</u></h5>
<h5>Discount: <u>{{__('config.currency').number_format($discount, 2)}}</u></h5>
<h5>Total: <u>{{__('config.currency').number_format($price-$discount, 2)}}</u></h5>