<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Services\CRUD;
use Request;

class Users extends Controller
{	
    /*
    |--------------------------------------------------------------------------
    | Users Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling meals and meta
    | includes a Class Services for assisting the application events and actions
    |
    */

    /**
     * Contains create url
     *
     * @return var
     */
    const createUrl = 'users/create/';

    /**
     * Contains verify email url
     *
     * @return var
     */
    const verifyEmailUrl = 'users/verify-email/';

    /**
     * Contains edit url
     *
     * @return var
     */
    const editUrl = 'users/edit';

    /**
     * Contains update url
     *
     * @return var
     */
    const updateUrl = 'users/update/';

    /**
     * Contains list all url
     *
     * @return var
     */
    const masterlistUrl = 'users/all-users';

    /**
     * Contains update password url
     *
     * @return var
     */
    const updatePasswordUrl = 'users/update-password';

    /**
     * Contains reset password url
     *
     * @return var
     */
    const resetPasswordUrl = 'customers/reset-password';

    /**
     * Contains view path
     *
     * @return var
     */
	const view = 'pages.users.';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->services = new CRUD(new \App\Repository\UsersRepository);
    }

    /**
     * Show's the application new page
     *
     * @return \Illuminate\Http\Response
     */
    public function new(): string
    {
    	return view(self::view.'new')->with([
    		'breadcrumb' 	=> $this->breadcrumb(),
    		'view'			=> self::view,
            'actionUrl'     => self::createUrl,
            'verifyEmailUrl' => self:: verifyEmailUrl,
            'masterlistUrl' => self::masterlistUrl,
            'edit'          => false,
            'roles'         => $this->services->repository->getRoles()
    	]);
    }


    /**
     * Show's the application new page
     *
     * @return \Illuminate\Http\Response
     */
    public function changePassword(): string
    {
    	return view(self::view.'password.change')->with([
    		'breadcrumb' 	=> $this->breadcrumb(),
    		'view'			=> self::view,
            'actionUrl'     => self::updatePasswordUrl,
            'verifyEmailUrl' => self:: verifyEmailUrl,
            'masterlistUrl' => self::masterlistUrl,
            'edit'          => false,
            'roles'         => $this->services->repository->getRoles()
    	]);
    }
    

    /**
     * Show's the application edit page
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id): string
    {   
        $response = [
            'breadcrumb'    => $this->breadcrumb(),
            'view'          => self::view,
            'actionUrl'     => self::updateUrl,
            'verifyEmailUrl'=> self:: verifyEmailUrl,
            'masterlistUrl' => self::masterlistUrl,
            'edit'          => true,
            'roles'         => $this->services->repository->getRoles()
        ];

        $response = array_merge($response, ['data' => $this->services->get($id)]);


        return view(self::view.'edit')->with($response);
    }

    /**
     * Show's the application list all page
     *
     * @return \Illuminate\Http\Response
     */
    public function masterlist(): string
    {
    	return view(self::view.'index')->with([
    		'breadcrumb' 	=> $this->breadcrumb(),
    		'view'			=> self::view,
            'editUrl'       => self::editUrl,
            'resetPasswordUrl' => self::resetPasswordUrl,
            'users'         => $this->services->getAll(),            
    	]);
    }

    /**
     * Handle a create request to the application
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(): array
    {
        return $this->services->store(Request::all());
    }

    /**
     * Handle a update request to the application
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(): array
    {
        return $this->services->update(Request::all());
    }

    /**
     * Handle a verify email request to the application
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function verifyEmail(): string
    {   
        return $this->services->verify(Request::get('email'));
    }

    /**
     * Handle a update request to the application
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Vaslidation\ValidationException
     */
    public function updatePassword(): array
    {
        $password = new \App\Services\Password;
        return $password->update(Request::all());
    }

}
