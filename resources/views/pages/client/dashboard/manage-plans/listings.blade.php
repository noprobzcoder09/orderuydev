@foreach($plans as $row)
@php($row = is_array($row) ? (object)$row : $row)


<div class="listing">
	<div class="row">
		<div class="col-sm-12 col-xs-12 col-md-3 col-lg-3 text-center pr-0">
			<img class="listing-image" src="{{asset('images/sample2.jpg')}}" width="60%">
		</div>
		<div class="col-sm-12 col-xs-12 col-md-9 col-lg-9 pl-0">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
					<span class="sub-title">&nbsp;</span>
					<span class="title">{{$row->name}}</span>
					{!! $row->status == 'cancelled' ? '<small class="bg-danger" style="padding: 2px;">Cancelled</small>' : '' !!}
					@if($row->status != 'cancelled')
					<div class="listing-body margin-top-10">

						@if(!empty($row->cycle_subscription_status) )
							<a href="javascript:;" onclick="cancelPlan(this, {{$row->id}}, {{$row->subscriptionsCycleId}}, '{{$row->cycle_subscription_status}}', '{{ $row->delivery_date }}')" class="listing-btn">CANCEL PLAN</a>
							
							<a style="display: {{empty($row->pausedDate) ? '' : 'none'}}" href="javascript:;" onclick="inputPause(this, {{$row->id}}, {{$row->subscriptionsCycleId}}, '{{$row->cycle_subscription_status}}', '{{ $row->delivery_date }}')" class="listing-btn stoptilldate">STOP PLAN TILL</a>
						@endif

						<a style="display: {{empty($row->pausedDate) ? 'none' : ''}}" href="javascript:;" onclick="cancelPausedDate(this, {{$row->id}}, {{$row->subscriptionsCycleId}})" class="listing-btn cancelpauseddate">
							RESUME <small>Paused until  {{$row->pausedDate}}</small>
						</a>

						<a href="javascript:;" onclick="viewPreviousSelections(this, {{$row->id}})" class="listing-btn">
							<span><span class="view-hide">VIEW</span> PREVIOUS WEEKS SELECTIONS</span>
						</a>
						
					</div>

					@endif
				</div>
				<div class="col-sm-12 col-xs-12 col-md-3 col-lg-3">
					<span class="price">${{$row->price}}/week</span>
				</div>
			</div>
		</div>
	</div>
</div>
@endforeach
@if(count($plans) <= 0)
<div class="listing">
	<div class="row">
		<div class="col-sm-12">
			<p class="text-center">{{__('config.manage-plans-no-data')}}</p>
		</div>
	</div>
</div>
@endif
