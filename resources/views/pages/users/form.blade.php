<form class="form-horizontal" id="user-form" action="{{url($actionUrl)}}" method="PUT">
    @csrf
    <input type="hidden" name="id" value="{{isset($data->id) ? $data->id : ''}}">
    <div class="form-group">
        <label for="email">Email</label>
        <input {{$edit ? 'readonly' : ''}} type="email" class="form-control" name="email" id="email" placeholder="Enter email here" value="{{isset($data->email) ? $data->email : ''}}">
    </div>

    <div class="row">
        <div class="form-group col-sm-12">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Enter name here" value="{{isset($data->name) ? $data->name : ''}}">
        </div>
    </div>
    <!--/.row-->

    <div class="form-group">
        <label for="select1">Role</label>
        <select id="role" name="role" class="form-control">
            <option value="">Please select</option>
            @foreach($roles as $role)
            <option {{isset($data->role) && $data->role == strtolower($role) ? 'selected' : ''}} value="{{strtolower($role)}}">{{$role}}</option>
            @endforeach
        </select>
    </div>

    <div class="row">
        <div class="col-sm-6 text-left">
            @if($edit)
            <a href="{{url($masterlistUrl)}}"> Back</a>
            @endif
        </div>
        <div class="col-sm-6 text-right">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> Submit</button>
        </div>
    </div>
</form>
