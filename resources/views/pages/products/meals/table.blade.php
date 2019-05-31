<table class="table table-hover table-align-middle mb-0 datatable">
    <thead>
        <tr>
            <th width="3%">S#</th>
            <th width="15%">SKU</th>
            <th>Meal</th>
            <th width="15%" class="text-left">Status</th>
            <th width="20%">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; ?>
        @foreach($data as $row)
        <tr>
            <td class="text-center">{{$row->id}}</td>
            <td>{{$row->meal_sku}}</td>
            <td>{{$row->meal_name}}&nbsp;{!!$row->vegetarian == 1 ? '<div class="badge badge-success"><i>Vegetarian</i></div>' : ''!!}</td>
            <td class="text-left">
                {!!$row->status == 1 ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-warning">Inactive</span>'!!}
            </td>
            <td>
                <a class="btn btn-info" href="{{url($editUrl).'/'.$row->id}}" title="Edit">
                  <i class="fa fa-edit "></i>
                </a>
                <a class="btn btn-danger deleteData" dusk="delete-{{$row->id}}" onclick="deleteData(this, {{$row->id}})" title="Delete">
                  <i class="fa fa-trash "></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
    </table>