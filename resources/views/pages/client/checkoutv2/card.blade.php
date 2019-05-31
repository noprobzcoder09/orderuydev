<div id="customer-card-form">
    <div class="row margin-top-20">
        <div class="col-md-12">
            <form id="credit-card-form" class="width-50">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <div class="input-group">
                                    <label>Name on Card</label>
                                    <span class="input-group">
                                        <span class="input-group-text input-group-text-remove-bg br-0"><i class="fa fa-user"></i></span>
                                        <input class="form-control credit-card-input pl-0 bl-0" id="card_name" name="card_name"  type="text" placeholder="Name on Card" value="">
                                    </span>
                                </div>
                            </div>
                        </div>  
                    </div>  
                </div>
                <div class="row margin-top-20">
                    <div class="col-md-12">
                        <div class="form-group" id="credit-card-wrapper">
                            <div class="row">
                                <div class="col-sm-6">
                                    Credit Card Number
                                </div>

                                <div class="col-sm-6 text-right">
                                    <ul id="card-icons">
                                        <li><img src="{{asset('images/mastercard.png')}}"></li>
                                        <!-- <li><img src="{{asset('images/americanexpress.png')}}"></li> -->
                                        <li><img src="{{asset('images/visa.png')}}"></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text input-group-text-remove-bg "><i class="fa fa-credit-card-alt"></i></span>
                                </span>
                                <input value="" class="form-control credit-card-input" id="card_number" name="card_number" type="text" placeholder="Card Number">
                                <span class="input-group-prepend card-date-wrapper">
                                    <input value="" class="form-control" id="card_expiration_date" name="card_expiration_date"  type="text" placeholder="MM / YY">
                                </span>
                                <span class="input-group-prepend card-cvc-wrapper">
                                    <input value="" class="form-control credit-card-input" id="card_cvc" name="card_cvc"  type="text" placeholder="CVC">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>