<form id="email-login-form" class="register-login-form" style="display: block;">
    @csrf
    <div class="login-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <input type="email" placeholder="Email Address" class="form-control" name="email" id="email">
                </div>
            </div>
        </div>

        <div class="row margin-top-20 text-center">
            <div class="col-md-12">
                <button type="submit" class="btn btn-lg btn-ecommerce">
                    Continue&nbsp;
                    <i class="fa fa-angle-right"></i>
                    <i class="fa fa-angle-right"></i>
                    <i class="fa fa-angle-right"></i>
                </button>
            </div>
        </div>
    </div>
</form>