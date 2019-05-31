<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;

use App\Services\Manageplan\Plan;
use App\Services\Manageplan\Worker;
use App\Services\Manageplan\Batch;
use App\Services\Manageplan\Discount;
use App\Services\Manageplan\SessionFacade;
use App\Services\Manageplan\Coupon;
use App\Services\Manageplan\Invoice;

use App\Services\Customers\Subscriptions\Extended\Auth as Authenticator;
use App\Services\Customers\PreviousWeekSubscriptions\Extended\Request;
use App\Services\Customers\PreviousWeekSubscriptions\Extended\Order;
use App\Services\Customers\PreviousWeekSubscriptions\Extended\SubscriptionFacade;

use App\Services\Customers\PreviousWeekSubscriptions\Traits\InfusionsoftCustomerProvider;

use Log;
use Auth;

use App\Services\Customers\PreviousWeekSubscriptions\Subscription as TraitSubscription;

class PreviousWeekSubscription extends Controller
{   

    use TraitSubscription;
    use InfusionsoftCustomerProvider;

    const view = 'pages.customers.';

    public function __construct()
    {   
        $this->middleware(function($request, $next) {
            $this->order = new Order('previous-weeke-checkout');
            $this->invoice = new Invoice;
            $this->coupon = new Coupon;
            $this->request = new Request;
            $this->batch = new Batch;
            $this->discount = new Discount;
            $this->plan = new Plan;
            $this->auth = new Authenticator();
            $this->worker = new Worker($this->order, $this->coupon);

            $this->sessionFacade = new SessionFacade(
                $this->request, $this->auth, $this->order, $this->coupon
            );

            $this->subscriptionFacade = new SubscriptionFacade(
                $this->request, $this->auth, $this->order, 
                $this->coupon, $this->discount, $this->batch
            );
            return $next($request);

        });
    }    
}



