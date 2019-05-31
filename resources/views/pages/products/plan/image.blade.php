 @if($edit)
<div class="form-group">
	@if(!empty($data->meal_plan_image))
    <img width="100%" src="{{url(str_replace('public','storage',$data->meal_plan_image))}}">
    @else
    <img width="100%" src="{{url('storage/images/default.jpg')}}">
    @endif
</div>
@endif