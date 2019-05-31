<table class="table table-hover table-align-middle mb-0 datatable">
    <thead>
        <tr>
            <th width="3%">Id</th>
            <th width="3%">Sku</th>
            <th>Plan Name</th>
            <th width="10%">No. Days</th>
            <th width="10%">No. Meals</th>
            <th width="15%">Price</th>
            <th width="15%">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
        <tr>
            <td class="text-center">{{$row->id}}</td>
            <td class="text-center">{{$row->sku}}</td>
            <td>{{$row->plan_name}} &nbsp;{!!$row->vegetarian == 1 ? '<div class="badge badge-success"><i>Vegetarian</i></div>' : ''!!}</td>
            <td class="text-center">{{$row->no_days}}</td>
            <td class="text-center">{{$row->no_meals}}</td>
            <td class="text-right">{{$row->price}}</td>
            <td>
                <a class="btn btn-info" href="{{url($editUrl).'/'.$row->id}}" title="Edit">
                  <i class="fa fa-edit "></i>
                </a>
                <a class="btn btn-danger deleteData" onclick="deleteData(this, {{$row->id}})" title="Delete">
                  <i class="fa fa-trash "></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>