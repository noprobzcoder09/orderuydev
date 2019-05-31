<div class="row margin-top-20">
    <div class="col-md-12">
        <form id="login-form">
            @csrf
            
            <div class="login-wrapper" style="display: {{Auth::check() ? 'none' : ''}};">
                <div class="row">
                    <div class="col-md-12">
                    @include('errors.messages',['id' => 'login-message','removestyle' => false])
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="email" placeholder="Email Address" class="form-control" name="email" id="email">
                        </div>
                        <div class="form-group" style="display: none;">
                            <input type="password" placeholder="Password" class="form-control" name="password" id="password">
                        </div>
                    </div>
                </div>
            </div>

            <div class="signup-wrapper" style="display: none;">
                <div class="row">
                    <div class="col-md-12">
                    @include('errors.messages',['id' => 'signup-message','removestyle' => false])
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="text-right"><a href="javascript:;" style="color: #000; text-decoration: underline;" class="" onclick="Login.showLoginForm()">Login here</a></p>
                        <div class="form-group">
                            <input type="email" placeholder="Email Address" class="form-control" name="semail" id="semail">
                        </div>
                        <div class="form-group">
                            <input type="password" placeholder="Password" class="form-control" name="spassword" id="spassword">
                        </div>
                         <div class="form-group">
                            <input type="password" placeholder="Confirm Password" class="form-control" name="scpassword" id="spassword">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row logged-wrapper" style="display: {{Auth::check() ? '' : 'none'}};">
                <div class="col-md-12">
                    <div class="alert">
                        <p>Hi! you are currently login as <myloggedname>{{ucfirst(strtolower(isset($account->name) ? $account->name : '')) }}</myloggedname>.</p>
                        <p>To change account, Click <a href="javascript:;" class="logged-me-out" onclick="Login.logout()">here</a> to logout;</p>
                    </div>
                </div>
            </div>
            
            <div class="row margin-top-20 text-center">
                <div class="col-md-12">
                     <a href="javascript:;" class="btn btn-lg btn-continue btn-ecommerce btn-addtocart next">
                        Continue&nbsp;
                        <i class="fa fa-angle-right"></i>
                        <i class="fa fa-angle-right"></i>
                        <i class="fa fa-angle-right"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>