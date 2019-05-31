<table class="table table-hover table-align-middle mb-0 datatable">
    <thead>
        <tr>
            <th width="3%">S#</th>
            <th>Coupon</th>
            <th>Type</th>
            <th>Value</th>
            <th>Used</th>
            <th>Used/Max Uses</th>
            <th>Min Order</th>
            <th>Expiry Date</th>
            <th width="20%">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; ?>
        @foreach($data as $row)

        @php
            $str_expiry_date = strtotime($row->expiry_date);
            $str_today_date = strtotime(date('Y-m-d'));

            $label_class = '';
            if($str_today_date > $str_expiry_date){
                $label_class = 'alert alert-danger';
            }else{
                $label_class = 'alert alert-success';
            }

        @endphp
        <tr>
            <td class="text-center">{{ $i++}}</td>
            <td class="text-left">{{$row->coupon_code}}</td>
            <td class="text-left">{{$row->discount_type}}</td>
            <td class="text-left">{{$row->discount_value}}</td>
            <td class="text-left">{{$row->used}}</td>
            <td class="text-left">{{$row->number_used}}/{{$row->max_uses}}</td>
            <td class="text-left">{{$row->min_order}}</td>
            <td class="text-left"><span class="{{$label_class}}">{{date('M d, Y',strtotime($row->expiry_date))}}</span></td>
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