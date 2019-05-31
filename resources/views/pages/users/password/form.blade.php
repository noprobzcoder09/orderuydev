<form class="form-horizontal" id="user-form" action="{{url($actionUrl)}}" method="PUT">
    @csrf
    <input type="hidden" name="id" value="{{isset($data->id) ? $data->id : ''}}">
    <div class="form-group">
        <label for="current-password">Current Password</label>
        <input type="password" class="form-control" name="current_password" id="current-password" placeholder="Current Password" required>
    </div>
    
    <div class="form-group">
        <label for="password">New Password</label>
        <input type="password" class="form-control" name="password" id="password" placeholder="New Password" required>
    </div>
    

    <div class="row">
        <div class="form-group col-sm-12">
            <label for="confirm-password">Confirm New Password</label>
            <input type="password" class="form-control" name="confirm_password" id="confirm-password" placeholder="Confirm New Password" required>
        </div>
    </div>
    <!--/.row-->

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
