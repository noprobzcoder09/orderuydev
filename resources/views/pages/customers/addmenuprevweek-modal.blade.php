<div class="modal fade" id="addmenuprevweek-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-success" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Menu For Previous Week</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
              
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" onclick="PreviousWeekMenu.createBilling()"  class="btn btn-success" data-action="billnow">Add & Bill Now</button>
                <button type="button" onclick="PreviousWeekMenu.create()"  class="btn btn-success" data-action="billcutover">Add & Mark as Paid</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->