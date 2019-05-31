<style type="text/css">
	#table-active-subs-week-menu tr td {
		border-bottom: 0 none !important;
	}
    .meals-menu {
        padding: 0;
        margin: 0 0 0 30px;
        list-style: circle;
    }
</style>
@if(count($data['lunch']) > 0)
Lunch
<ul class="meals-menu">
    @foreach($data['lunch'] as $meal)
    <li>{{$meal}}</li>
    @endforeach
</ul>
@endif
@if(count($data['dinner']) > 0)
Dinner
<ul class="meals-menu">
    @foreach($data['dinner'] as $meal)
    <li>{{$meal}}</li>
    @endforeach
</ul>
@endif

<!-- modify button -->
@if (!empty($has_default_cycle_meals) && auth()->user()->role != 'customer')
    <div class="clearfix margin-top-20">
        <button type="button" class="btn btn-success btn-sm" onclick="PreviousSelections.modify({{ $subscriptions_cycle_id }});">Modify</button>
    </div>

    <div class="clearfix margin-top-20">
        <div id="previous-menu-selections-{{ $subscriptions_cycle_id }}"></div>
    </div>
@endif