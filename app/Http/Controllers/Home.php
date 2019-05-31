<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Log;

class Home extends Controller
{	

    /**
     * Contains view path 
     *
     * @return var
     */
	const view = 'pages.home.';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
	public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Show's the application default page
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): string
    {
        if (!(new \App\Repository\UsersRepository)->isAdmin()) {
            abort('404','Access Denied.');
        }
        Log::info("Hello world!");
    	return view(self::view.'index')->with([
    		'breadcrumb' 	=> $this->breadcrumb(),
    		'view'			=> self::view
    	]);
    }
}
