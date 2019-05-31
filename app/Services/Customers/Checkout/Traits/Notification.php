<?php

namespace App\Services\Customers\Checkout\Traits;

// use App\Mail\CustomInvoiceEmail;
use App\Mail\SubscriptionAccount;
use App\Repository\SubscriptionRepository;

use Log;
use Mail;

Trait Notification
{   
 
  public function sendEmailNotification()
    {   
        $model = $this->subscriptionFacade->getEloquentSubscription();
        // email user account
        $this->log->info('Email customer details to '.$this->user->getEmail());
        Mail::to($this->user->getEmail())
            ->queue(new SubscriptionAccount($model));
        
    }

}


