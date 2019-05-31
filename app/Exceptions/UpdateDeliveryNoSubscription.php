<?php

namespace App\Exceptions;

use Exception;
use Log;

class UpdateDeliveryNoSubscription extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report($exception)
    {
        Log::debug($Exception);
    }
}