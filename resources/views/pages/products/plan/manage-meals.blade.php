<div class="m-portlet m-portlet--responsive-tablet-and-mobile">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-clipboard"></i>
                </span>
                <h3 class="m-portlet__head-text m--font-brand">
                    Manage Meals Status
                </h3>
            </div>
        </div>
    </div>

    <div class="m-portlet__body card-body">
        <div class="row">
            <div class="col-sm-12">
                <p>
                    <strong>Delivery Date:</strong> {{date('l dS F Y', strtotime($cycle->delivery_date))}}<br />
                    <strong>Cutover Date:</strong> {{date('l dS F Y', strtotime($cycle->cutover_date))}}
                </p>
            </div>
            <div class="col-sm-12 text-right" style="display: none;">
                @php($active1 = url('products/meals/active'))
                @php($inactive1 = url('products/meals/inactive'))
                <a href="javascript:;" onclick="showMeals('{{$active1}}')" class="btn btn-info btn-change-click" data-ck-class="btn-success" data-ckd-class="btn-info">
                    Manage Active
                </a>
                <a href="javascript:;" onclick="showMeals('{{$inactive1}}')"class="btn btn-info btn-change-click" data-ck-class="btn-success" data-ckd-class="btn-info">
                    Manage Inactive
                </a>
            </div>
            <div class="col-sm-12">
                @include($view.'scheduler-form', ['active' => $active, 'inactive' => $inactive])
            </div>
        </div>
    </div>
</div>