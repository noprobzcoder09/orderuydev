<table class="table table-hover table-align-middle mb-0 datatable">
    <thead>
        <tr>
            <th>Name</th>
            <th width="15%">Email</th>
            <th width="10%">Role</th>
            <th width="15%">Last Login</th>
            <th width="15%">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $row)
        <tr>
            <td>{{$row->name}}</td>
            <td class="text-left">{{$row->email}}</td>
            <td class="text-left">{{$row->role}}</td>
            <td class="text-left">{{ date('D, F d, Y H:i') }}</td>
            <td>
                <a class="btn btn-info" href="{{url($editUrl).'/'.$row->id}}" title="Edit">
                  <i class="fa fa-edit "></i>
                </a>
                <a class="btn btn-info" href="javascript:void(0);" onclick="Customer.resetPassword({{$row->id}});" title="Reset Password">
                  <i class="fa fa-key "></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>    