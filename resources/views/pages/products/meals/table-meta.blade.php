<table class="table table-responsive-sm table-striped table-bordered datatable">
    <thead>
        <tr>
            <th width="3%">S#</th>
            <th>Field</th>
            <th>Value</th>
            <th width="30%">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; ?>
        @foreach($metas as $row)
        <tr>
            <td class="text-center">{{ $i++}}</td>
            <td>{{$row->meta_key}}</td>
            <td>{{$row->meta_value}}</td>
            <td>
                <a class="btn btn-info" href="javascript:;" onclick="editMetaData(this, {{$row->id}})">
                  <i class="fa fa-edit "></i>
                </a>
                <a class="btn btn-danger deleteData" onclick="deleteMetaData(this, {{$row->id}})">
                  <i class="fa fa-trash "></i>
                </a>
            </td>
        </tr>
        @endforeach
        @if(count($metas) <= 0)
        <tr>
            <td colspan="4">No record/s added.</td>
        </tr>
        @endif
    </tbody>
</table>    