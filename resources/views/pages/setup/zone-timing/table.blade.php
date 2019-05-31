<table class="table table-hover table-align-middle mb-0 datatable">
    <thead>
        <tr>
            <th>S#</th>
            <th>Zone</th>        
            <th>Cutoff Day</th>    
            <th>Delivery Day</th>
            <th width="20%">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; ?>
        @foreach($data as $row)
        <tr>
            <td class="text-center">{{ $i++}}</td>
            <td class="text-left">{{$row->zone_name}}</td>
            <td class="text-left">{{ $row->cutoff_day.' at '.date('h:i A', strtotime($row->cutoff_time))}}</td>
            <td class="text-left">{{$row->delivery_day}}</td>
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