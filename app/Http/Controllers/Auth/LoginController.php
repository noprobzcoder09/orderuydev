<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

use App\Mail\ResetPassword;
use Illuminate\Http\Request;
use Mail;
use Illuminate\Support\Str;
use \App\Services\CartSession;
use App\Traits\Auditable;

class LoginController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, SendsPasswordResetEmails, Auditable;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'email';
    }

    public function success()
    {           
        return [
            'success'   => 1,
            'redirectPath' => url((new \App\Repository\UsersRepository)->redirect())
        ];
    }

     /**
     * Get the response for a successful password reset link.
     *
     * @param  string  $response
     * @return array
     */
    protected function sendResetLinkResponse() {
        return ['success' => true, 'message' => __('passwords.sent')];
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return array
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return ['success' => false, 'message' => trans($response)];
    }    

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);

        $user_found = \App\Models\Users::where('email', $request->email)->first();

        $this->audit('User Logged In', $user_found->name . ' has been logged in to his/her account.', 'Email used: ' . $request->email);
        
        return $this->success();
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {       
        return ['success' => false, 'message' =>trans('auth.failed')];
    }

     /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {   
        $this->guard()->logout();

        $request->session()->invalidate();

        if ($request->ajax()) {
            return [
                'success'   => 1
            ];
        }

        return $this->loggedOut($request) ?: redirect('/');
    }
}

