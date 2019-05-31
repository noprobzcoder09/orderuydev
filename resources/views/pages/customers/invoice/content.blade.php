
<div class="card">
    <div class="card-header">
        Invoice
        <strong>#90-98792</strong>
        <a href="#" class="btn btn-sm btn-info float-right" onclick="javascript:window.print();"><i class="fa fa-print"></i> Print</a>
    </div>
    <div class="card-body">
        <div class="row mb-4">

            <div class="col-sm-4">
                @include($view.'customer')
            </div>
            <!--/.col-->

            <div class="col-sm-4">
                @include($view.'delivery')
            </div>
            <!--/.col-->

            <div class="col-sm-4">
                @include($view.'details')
            </div>
            <!--/.col-->

        </div>
        <!--/.row-->

        <div class="table-responsive-sm">
            @include($view.'table')
        </div>

        <div class="row">

            <div class="col-lg-4 col-sm-5 ml-auto">
                @include($view.'summary-table')
            </div>
            <!--/.col-->

        </div>
        <!--/.row-->
    </div>
</div>