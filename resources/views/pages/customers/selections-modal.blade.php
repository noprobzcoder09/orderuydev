<div class="modal fade" id="selections-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-success" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Menu Selections</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
               @include($view.'selections.content',['view' => $view.'selections.'])
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->