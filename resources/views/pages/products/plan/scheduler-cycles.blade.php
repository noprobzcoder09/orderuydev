<?php
    
    function getStart($date)
    {
        return date('Y-m-d', strtotime($date.' -7 day'));
    }
?>
<table class="table table-hover table-align-middle mb-0 datatable">
    <thead>
        <tr>
            <th width="3%">ID</th>
            <th>Cycle Start Date</th>
            <th>Cutover Date</th>
            <th>Delivery Date</th>
            <th>Status</th>
            <th width="15%">Actions</th>
        </tr>
    </thead>
    <tbody>
        @php($i=1)
        @foreach($cycles as $row)
        <tr>
            <td class="text-center">{{$row->id}}</td>
            <td class="text-center">{{date('l dS F Y', strtotime(getStart($row->cutover_date)))}}</td>
            <td class="text-center">{{date('l dS F Y', strtotime($row->cutover_date))}}</td>
            <td class="text-center">{{date('l dS F Y', strtotime($row->delivery_date))}}</td>
            <td class="text-center">
                @if($row->status == 1)
                <span class="badge badge-success">Active</span>
                @elseif($row->status == -1)
                <span class="badge badge-success">Past</span>
                 @elseif($row->status == 0)
                <span class="badge badge-warning">Future Cycle</span>
                @endif
            </td>
            <td>
                <a class="btn btn-info" href="javascript:;" onclick="manageMealStatus({{$row->id}})" title="Manage">
                  <i class="fa fa-eye "></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>