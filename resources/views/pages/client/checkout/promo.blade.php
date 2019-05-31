<div class="row margin-top-20">
    <div class="col-md-12">
        <form id="promo-form" class="width-50">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="input-group" id="promo-container" style="display: none;">
                            <input placeholder="Coupon / Promo Code" class="form-control remove-border-shadow" id="coupon_code" name="coupons" type="text">
                            <span class="input-group-prepend cursor-pointer" onclick="Coupons.validate()">
                                <span class="input-group-text"><i class="fa fa-check"></i></span>
                            </span>
                        </div>
                        <div id="promo-add-container" class="text-center" style="display: block">
                            <a href="javascript:;" onclick="Coupons.addNewPromoCode()">Add Promo Code</a>
                        </div>
                        <em id="coupon_error" style="color: red; font-size: 12px;display: none;">Error Invalid</em>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>