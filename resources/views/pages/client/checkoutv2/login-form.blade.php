<form id="login-form" class="register-login-form" style="display: none;">
    @csrf
    <div class="login-wrapper">
        <div class="row">
            <div class="col-md-12">
                <p class="text-right"><a href="javascript:;" style="color: #000; text-decoration: underline;" class="" onclick="Register.showRegistrationForm()">Register here</a></p>
                <div class="form-group">
                    <input type="email" placeholder="Email Address" class="form-control" name="email" id="email">
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Password" class="form-control" name="password" id="password">
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