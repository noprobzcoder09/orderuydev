<?php

namespace App\Http\Controllers\Customers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\Password as PasswordManager;
use Auth;
use App\Traits\Auditable;

class Password extends Controller
{   
    use Auditable;
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
        $manager = new PasswordManager(Auth::id());
        $user_found = (new \App\Models\Users)->find(Auth::id());
        $this->audit('User Changed Password', $user_found->name . ' changed his/her password.', '');
        return $manager->update();
    }

}
