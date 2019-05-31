<div id="datepicker-wrapper" style="display: none;">
    <div id="date-container">
        <div class="input-group">
            <span class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
            </span>
            <input type="text" class="form-control date" name="date" placeholder="Enter Date here">
            <span class="input-group-prepend cursor-pointer" onclick="SaveStopAllTillDate(this, $(this).closest('div').find('.date').val())">
                <span class="input-group-text"><i class="fa fa-check"></i></span>
            </span>
            <span class="input-group-prepend cursor-pointer" onclick="closeStopTillDate(this)">              
                <span class="input-group-text"><i class="fa fa-times"></i></span>
            </span>
        </div>
    </div>
</div>