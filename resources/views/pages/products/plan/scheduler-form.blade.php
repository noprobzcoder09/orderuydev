<form class="form-horizontal" id="scheduler-form"  method="POST">
    @csrf
    <input type="hidden" name="cycle_id" value="{{isset($cycle->id) ? $cycle->id : ''}}">
    <div class="row">
        <div class="form-group col-sm-6">
            <label for="add_all"><input type="checkbox" name="add_all" id="add_all"> Select All</label> <br />
            <label for="cycle_id">Meals to add</label>
            <select class="form-control select2-single" id="meal_ids_add" name="meal_ids_add" multiple="" required >
                @foreach($active as $row)
                <option {{ count($idsadded) > 0 ? (in_array($row->id, $idsadded) ? 'selected' : '') : '' }} value="{{$row->id}}">{{$row->meal_name}}. {!! in_array($row->id, $vegetarian) ? '<span style="font-sze: 8px;">Vege</span>' : '' !!}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-6">
            <label for="cycle_id">Meals to remove</label>
            <select class="form-control select2-single" id="meal_ids_remove" name="meal_ids_remove" multiple="">
                @foreach($active as $row)
                <option {{  (count($idsremoved) > 0 ? (in_array($row->id, $idsremoved) ? 'selected' : '') : '')}}  value="{{$row->id}}">{{$row->meal_name}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 text-right">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> Save</button>
        </div>
    </div>
</form>