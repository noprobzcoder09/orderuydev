<?php

namespace App\Http\Controllers\Customers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\Profile as ProfileManager;
use Auth;

class Profile extends Controller
{   

    /*
    |--------------------------------------------------------------------------
    | Dashboard Customer Delivery Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling updates for delivery details
    |
    */

    public function update()
    {   
        $manager = new ProfileManager(Auth::id());
        return $manager->update();
    }

}
