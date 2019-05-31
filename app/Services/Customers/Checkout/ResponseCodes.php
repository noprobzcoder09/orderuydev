<?php

namespace App\Services\Customers\Checkout;
use Auth;
use Request;
use Log;

Class ResponseCodes
{   
   
   public function get()
   {
        return [
            'authExpired' => __('codes.authExpired'),
            'cardIinvalidExisting'=> __('codes.cardIinvalidExisting'),
            'rulesInvalid'=> __('codes.rulesInvalid'),
        ];
   }

}
