<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;

use App\Services\Manageplan\Plan;
use App\Services\Manageplan\Worker;
use App\Services\Manageplan\Batch;
use App\Services\Manageplan\Discount;
use App\Services\Manageplan\Invoice;

use App\Services\Customers\Checkout\Extended\SubscriptionFacade;
use App\Services\Customers\Checkout\Extended\Order;
use App\Services\Customers\Checkout\Extended\Request;
use App\Services\Customers\Checkout\Extended\Auth as Authenticator;
use App\Services\Customers\Checkout\Extended\Coupon as Coupon;

use App\Services\Customers\Checkout\Traits\Coupon as CouponTrait;
use App\Services\Customers\Checkout\Traits\Subscription as SubscriptionTrait;
use App\Services\Customers\Checkout\Traits\Billing as BillingTrait;
use App\Services\Customers\Checkout\Traits\Card as CardTrait;
use App\Services\Customers\Checkout\Traits\User as UserTrait;
use App\Services\Customers\Checkout\Traits\Contact as ContactTrait;
use App\Services\Customers\Checkout\Traits\Invoice as InvoiceTrait;
use App\Services\Customers\Checkout\Traits\Notification as NotificationTrait;
use App\Services\Customers\Checkout\Traits\Tags as TagsTrait;
use App\Services\Customers\Checkout\Checkout as CheckoutTrait;
use App\Services\Customers\Checkout\SessionRegistration;
use App\Services\Customers\Checkout\Traits\InfusionsoftCustomerUpdate;

use App\Services\Customers\Checkout\User as UserModel;

use \App\Services\Log;

use Auth;

class Checkout extends Controller
{   

    use 
    CheckoutTrait, 
    ContactTrait, 
    CardTrait, 
    UserTrait, 
    CouponTrait, 
    SubscriptionTrait, 
    BillingTrait, 
    InvoiceTrait,
    NotificationTrait,
    TagsTrait;

    use InfusionsoftCustomerUpdate;

    const view = 'pages.client.checkoutv2.';

    public function __construct()
    {   
        $this->middleware(function($request, $next) {
            $this->order = new Order;
            $this->coupon = new Coupon;
            $this->request = new Request;
            $this->batch = new Batch;
            $this->discount = new Discount;
            $this->plan = new Plan;
            $this->auth = new Authenticator;
            $this->invoice = new Invoice;
            $this->user = new UserModel;

            $this->worker = new Worker($this->order, $this->coupon);
            
            $this->subscriptionFacade = new SubscriptionFacade (
                $this->request, $this->auth, $this->order,  
                $this->coupon, $this->discount, $this->batch
            );

            $this->sessionRegister = new SessionRegistration;

            return $next($request);

        });
            
        $this->log = new Log('checkout','logs/'.date('Y').'/'.date('m').'/system-'.date('Y-m-d').'.log');
    }

    private function sendSuccessResponse()
    {
        return [
            'code' => 200,
            'success' => true,
            'message' => sprintf(__('crud.created'),'New Plan')
        ];
    }

    private function getCheckoutUrl()
    {
        return url('checkout');
    }
    
}



