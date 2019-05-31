Hi! {{$name}},

We received your order. Below are the details.

<br />
<p>{{$plan}}</p>

@if(count($lunch) > 0)
<p>LUNCH</p>
<ol>
	@foreach($meals as $row)
		@if(in_array($row->id, $lunch))
		<li>{{$row->meal_name}}</li>
		@endif
	@endforeach
</ol>
@endif

@if(count($dinner) > 0)
<p>DINNER</p>
<ol>
	@foreach($meals as $row)
		@if(in_array($row->id, $dinner))
		<li>{{$row->meal_name}}</li>
		@endif
	@endforeach
</ol>
@endif