<?php

namespace App\Http\Controllers\Customers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\Card as CardManager;
use App\Services\Dashboard\Subscription;
use App\Services\Dashboard\BillingIssueCharge;
use App\Services\Dashboard;
use Auth;

class BillingIssue extends Controller
{   

    /**
     * Contains view path 
     *
     * @return var
     */
    const view = 'pages.client.dashboard.billing-issue.';

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

    public function getBillingIssueSubscriptions()
    {
        $userId = Auth::id();
        $dashboard = new Dashboard($userId);

        $response = array(
            'unpaidSubscriptions' => $dashboard->getUnpaidSubscriptionsToArray($userId),
            'forDeliverySubscriptions' => $dashboard->getForDeliverySubscriptionsToArray($userId)
        );

        return array(
            'nounpaidsubscriptions' => (count($response['unpaidSubscriptions']) <= 0),
            'contents' => view(self::view.'subscriptions', $response)->render()
        );

    }

    public function cancelSubscriptionCycle(int $subscriptionId,int $subscriptionCycleId)
    {        
        if (empty($subscriptionCycleId)) {
            throw new Exception("Subscription cycle should not be empty.", 1);
            
        }

        $subscription = new Subscription(Auth::id());
        return $subscription->cancelSubscriptionCycle($subscriptionId, $subscriptionCycleId);
    }

    public function cancelSubscription(int $subscriptionId, int $subscriptionCycleId)
    {        
        if (empty($subscriptionId) || empty($subscriptionCycleId)) {
            throw new Exception("Subscription should not be empty.", 1);
            
        }

        $subscription = new Subscription(Auth::id());
        return $subscription->cancelSubscription($subscriptionId, $subscriptionCycleId);
    }

    public function chargeCard()
    {
        $charge = new BillingIssueCharge(Auth::id());
        return $charge->handle();
    }

    public function updateCardAndBill()
    {
        $card = new CardManager(Auth::id());
        $card->createAndUpdateDefaultCard();

        return $this->chargeCard();
    }
}