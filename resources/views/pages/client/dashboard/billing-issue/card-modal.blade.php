<div class="modal fade" id="creditcard-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-success" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Credit Card Form</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                    @include('errors.messages',array('id' => 'card-message'))
                    </div>
                </div>
                @include($view.'card')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" onclick="Action.updateAndBillCard()" class="btn btn-success">Update & Bill</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->