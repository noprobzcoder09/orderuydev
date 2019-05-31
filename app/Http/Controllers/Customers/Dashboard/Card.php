<?php

namespace App\Http\Controllers\Customers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\Card as CardManager;
use Auth;

class Card extends Controller
{   

    /*
    |--------------------------------------------------------------------------
    | Dashboard Customer Card Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling new card
    | It is being use in customer dashboard page
    | Thus, is has a fix user id which base on the current login
    |
    */

    public function create()
    {   
        $manager = new CardManager(Auth::id());
        return $manager->create();
    }

}
