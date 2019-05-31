@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-6">
        @include('errors.messages')
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="card" id="meal-container">
            <form id="form-meta">
                <div class="card-header">
                    <strong>Meals</strong>
                    <small>- {{ucfirst($meal)}}</small>
                </div>
                <div class="card-body">
                    @include($view.'form-meta')
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> Add New</button>
                    <button type="reset" onclick="window.location.href=url.mealnew" class="btn btn-sm btn-danger"><i class="fa fa-ban"></i> Back</button>
                </div>
            </form>
        </div>
    </div>
    <!--/.col-->
    <div class="col-sm-6">
        @include('errors.messages')
        <div class="card" id="meta-table-container">
            <div class="card-header">
                <strong>Meta</strong>
                <small>List</small>
            </div>
            <div class="card-body">
                @include($view.'table-meta')
            </div>
            <div class="card-footer">
               
            </div>
        </div>
    </div>
    <!--/.col-->
</div>

@endsection


@section('css')
    
@endsection

@section('script')
    

    <script type="text/javascript">

        var baseUrl = "{{url('/')}}/products/meals/";
        var url = {
            mealnew: baseUrl+'new',
            meta: baseUrl+'meta/add',
            metaDelete: baseUrl+'meta/delete',
        }

        $('#form-meta').submit( function() {

            if (!isFormValid('#form-meta')) {
                    return false;
                }

                System.blockUI('#meal-container');

                System.setAjaxRequest(
                    url.meta,
                    $('#form-meta').serialize(),
                    'POST',
                    function(response) {
                                if (response.status == 200)
                                {   
                                    System.unblockUI();
                                    if (response.success) {

                                        System.successMessage(response.messages);
                                        $('#meta-table-container .card-body').html(response.metas);
                                        $('#form-meta input[type="text"]').val('');
                                        return;
                                    } 

                                    System.errorMessage(response.messages);
                                }
                    },
                    function() {
                                System.errorMessage();
                                System.unblockUI();
                    }
                );

            return false;
        });


        function remove(_this, id) {
            var _this = $(_this).closest('tr');
            System.blockUI(_this);

            System.setAjaxRequest(
                    url.metaDelete,
                    'id='+id,
                    'POST',
                    function(response) {
                        if (response.status == 200)
                        {   
                            System.unblockUI();
                            if (response.success) {

                                _this.remove();
                                System.successMessage(response.messages);
                                return;
                            } 

                            System.errorMessage(response.messages);
                        }
                    },
                    function() {
                                System.errorMessage();
                                System.unblockUI();
                    }
                );
        }

    </script>
@endsection