<table class="table table-hover table-align-middle mb-0 datatable">
    <thead>
        <tr>
            <th width="3%">S#</th>
            <th>Cutoff Day</th>  
            <th>Delivery Day</th>      
            <th>Enabled</th>      
            <th width="20%">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; ?>
        @foreach($data as $row)
        <tr>
            <td class="text-center">{{ $i++}}</td>
            <td class="text-left">{{$row->cutoff_day.' '.date('h:i A', strtotime($row->cutoff_time))}}</td>
            <td class="text-left">{{$row->delivery_day}}</td>
            <td class="text-left">
                <label class="switch switch-3d switch-primary">
                    <input type="checkbox" class="switch-input" onchange="disabledData(this, {{$row->id}}, $(this).prop('checked'))" {{$row->disabled == '1' ? '' : "checked=''"}}">
                    <span class="switch-label"></span>
                    <span class="switch-handle"></span>
                </label>

            </td>
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