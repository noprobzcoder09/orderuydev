<div class="modal fade" id="creditcard-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-success" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Credit Card Form</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                @include($view.'card-form')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" onclick="BillingIssue.resetModal()" id="btn-back" class="btn btn-warning" style="display: none;">Back</button>
                <button type="button" onclick="BillingIssue.addNewCard()" id="btn-add-new-card" class="btn btn-success">Add New Card</button>
                <button type="button" onclick="BillingIssue.createNewCard()" id="btn-save-card" class="btn btn-success" style="display: none">Save Card</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->