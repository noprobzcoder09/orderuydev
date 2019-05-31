<?php
    global $records;
    global $customerIds;
    global $activeCycleIds;
    global $filterStatus;

    $filterStatus = trim(strtolower($status));

    $records = $data;

    $customer = new \App\Services\Customer(new \App\Repository\CustomerRepository);

    function getPlans($userId)
    {   
        global $records;

        $data = [];
        $ids = [];
        foreach($records as $row) {
            if ($userId == $row->user_id) {
                $data[$row->id] = $row->plan_name. ' '. $row->vegetarian;
                $ids[$row->user_id][] = [
                    'timing_id' => $row->delivery_timing_id,
                    'zone_id' => $row->delivery_zone_id,
                    'plan_id' => $row->meal_plans_id
                ];
            }
        }

        return $data;
    }

    $filter = [];
    $plansData = [];
    foreach($records as $row) {
        if (!in_array($row->user_id, $filter)) {
            $plansData[] = $row;
            $filter[] = $row->user_id;
        }
    }
    
?>
<table class="table table-hover table-align-middle mb-0 datatable">
    <thead>
        <tr>
            <th>Customer</th>
            <th>Meals Plan</th>
            <th>Location/Timing</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @php($customerIds = [])
        @foreach($plansData as $row)
        <tr>
            <td>{{$row->name}}</td>
            <td>{!!implode('<br />',getPlans($row->user_id))!!}</td>
            <td>{!! $row->zone_name.' / Delivery: '.$row->delivery_day !!}</td>
            <td>
                <?php
                    switch (strtolower($row->user_status)) {
                        case 'active': $badge = 'badge-success'; break;
                        case 'failed':
                        case 'cancelled': $badge = 'badge-danger'; break;
                        case 'paused': $badge = 'badge-primary'; break;
                        default: $badge = 'badge-warning';break;
                    }
                ?>
                {!! '<span class="badge '.$badge.'">'.$row->user_status.'</span>' !!}
            </td>
            <td>
                <a class="btn btn-success" href="{{$url}}edit/{{$row->user_id}}">
                  <i class="fa fa-search-plus "></i>
                </a>
            </td>
            @php($customerIds[] = $row->user_id)
        </tr>
        @endforeach
    </tbody>
</table>