<form id="credit-card-form">
    <input type="hidden" name="userId" value="{{$id}}">
    <div class="row">
        <div class="col-md-12">
        @include('errors.messages', array('id' => 'card-message'))
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row">

                <div class="form-group m-form__group col-md-12">
                    <label>
                        Name on Card
                    </label>
                    <div class="input-group m-input-group m-input-group--square">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <span class="la la-user"></span>
                            </span>
                        </div>
                        <input type="text" id="card_name" value="" name="card_name" class="form-control m-input" placeholder="Name on Card">
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
                        <span class="input-group-text input-group-text-remove-bg "><span class="fa fa-credit-card-alt"></span></span>
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

<style type="text/css">
    .credit-card-input:focus, .remove-border-shadow:focus {
    border-color: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
}

/*#credit-card-wrapper input[type="text"] {
    border-left: 0 none !important;
    padding-left: 0 !important;
}*/

#card-icons {
    float: right;
    padding: 0;
    margin: 0;
}
#card-icons li {
    float: left;
    list-style: none;
}

#card-icons li img {
    width: 40px;
}   

#card_number {
    border-right: 0 none !important;
}
#card_date, #card_cvc {
    width: 30%;
}

.tab-content {
    width: 80%;
    margin: 0 auto;
}

.width-50 {
    width: 50%;
    margin: 0 auto;
}

.bold {
    font-weight: bold;
}

.nav-link > a {
    cursor: default;
    text-decoration: none;
}

.nav-link > a:hover {
    text-decoration: none;
}

.card-date-wrapper, .card-cvc-wrapper {
    width: 15%;
}
</style>