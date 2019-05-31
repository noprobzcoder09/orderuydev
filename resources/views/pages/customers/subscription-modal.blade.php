<div class="modal fade" id="active-subscription-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-success" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">New Subscription</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                    @include('errors.messages', array('id' => 'subscriptionmodal-message'))
                    </div>
                </div>
                @include($view.'customer.subscription-form')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" onclick="ManagePlan.createBilling()" class="btn btn-success" data-action="billnow">Save & Bill Now</button>
                <button type="button" onclick="ManagePlan.create()" class="btn btn-success" data-action="billcutover">Save & Bill at cutover</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->