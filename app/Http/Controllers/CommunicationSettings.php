<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\CRUD;
use Configurations as Configuration;

class CommunicationSettings extends Controller
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
    const updateUrl = 'settings/communication-settings/update';

        /**
     * Contains view path
     *
     * @return var
     */
	const view = 'pages.settings.communication-settings.';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Show's the application new page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view(self::view.'subscription-text')->with([
    		'breadcrumb' 	=> $this->breadcrumb(),
    		'view'			=> self::view,
            'actionUrl'     => self::updateUrl,   
            'manageSubscriptionText' => Configuration::getManageSubscriptionText()         
    	]);
    }


    public function update()
    {
        
        $update = Configuration::where('slug','manage-subscription-text')->update(['value' => $this->request->manage_subscription_text]);
       
        if ($update) {
            return response()->json(['success' => 200,'message' => 'Successfully updating Subscription text', 'status' => 200]);
        }

        return response()->json(['success' => false,'message' => 'Error on Server', 'status' => 500]);
    }

    
}
