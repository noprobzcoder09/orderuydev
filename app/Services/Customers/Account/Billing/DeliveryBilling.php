<?php

namespace App\Services\Customers\Account\Billing;

use App\Services\Customers\Account\Billing\Data\Subscriptions;
use App\Services\Customers\Account\Billing\Data\SubscriptionsSelections;
use App\Services\Customers\Account\Billing\Data\Discount;
use App\Services\Customers\Account\Billing\Data\User;
use App\Services\Customers\Account\Billing\Extended\Order;
use App\Services\Customers\Account\Billing\Extended\Coupon;
use App\Services\Manageplan\Worker;
use \App\Services\Validator;

use App\Services\Customers\Account\Billing\Dto\UserDetailsDto;
use App\Services\Customers\Account\Billing\Dto\SubscriptionsDto;
use App\Services\Customers\Account\Billing\Dto\SubscriptionsSelectionsDto;
use App\Services\Customers\Account\Billing\Dto\SubscriptionsCycleStatusDto;
use App\Services\Customers\Account\Billing\Dto\SubscriptionsStatusDto;
use App\Services\Customers\Account\Billing\Dto\ProductDto;
    
use App\Services\Customers\Account\Billing\Dto\InvoiceDto;
use App\Services\Customers\Account\Billing\CardID;
use App\Services\Customers\Account\Billing\Dto\CalculateSharedCouponDiscountDto;

Class DeliveryBilling
{  
    private $userId;
    private $orderId = null;
    private $invoiceId = null;
    private $subscriptionsData = array();
    private $subscriptionsForResumeData = array();
    private $userDetails = array();

    public function __construct(
        int $userId, 
        int $deliveryTimingId, 
        int $previousCycleId, 
        \DateTime $deliveryDate,
        \DateTime $currentDeliveryDate
    ) {   
        $this->userId = $userId;
        $this->deliveryTimingId = $deliveryTimingId;
        $this->previousCycleId = $previousCycleId;
        $this->deliveryDate = $deliveryDate->format('Y-m-d');
        $this->currentDeliveryDate = $currentDeliveryDate->format('Y-m-d');

        $this->subscriptions = new Subscriptions;
        $this->subscriptionsSelections = new SubscriptionsSelections;
        $this->user = new User;
        $this->discount = new Discount;

        $this->initUser();
        $this->load();
    }

    public function load()
    {   
        $itemType = (int)env('PRODUCT_ITEMTYPE');
        foreach($this->subscriptions->get($this->userId) as $row) 
        {   
            $selections = $this->subscriptionsSelections->getPendingWithPreviousCycleId(
                $row->user_id, $row->id, $this->previousCycleId
            );

            $selections = new SubscriptionsSelectionsDto(
                new SubscriptionsCycleStatusDto($selections->cycle_subscription_status ?? ''),
                $selections->id ?? 0,
                $selections->cycleId ?? 0,
                $selections->discount_id ?? 0,
                json_decode($selections->meta_data ?? null) ?? []
            );

            if (!$selections->isEmpty()) {

                $subscriptionsStatus = new SubscriptionsStatusDto(
                    $row->status,
                    new \DateTime($row->paused_till),
                    new \DateTime($this->deliveryDate),
                    new \DateTime($this->currentDeliveryDate)
                );

                $product = new ProductDto(
                    $row->meal_plans_id,
                    $row->ins_product_id,
                    $itemType,
                    $quantity = 1,
                    $row->price,
                    $row->plan_name
                );

                $subscriptions = new SubscriptionsDto(
                    $row->id, 
                    $selections->getId(),
                    $product,
                    $selections->getDiscountId(),
                    $selections->getCoupons(),
                    $subscriptionsStatus->getStatus()
                );

                if ($subscriptionsStatus->isForBilling()) {
                    $this->subscriptionsData[] = $subscriptions;
                } 

                if ($subscriptionsStatus->isPaused()) {
                    if ($subscriptionsStatus->isPauseResumeNow()) {
                        $this->subscriptionsForResumeData[] = $subscriptions;
                    }
                }
            }
        }

        $this->calculateDiscountInit();
    }

    private function initUser()
    {
        $user = $this->user->get($this->userId);
        $this->userDetails = new UserDetailsDto(
            $user->user_id,
            new CardID($user->card_ids, $user->default_card), 
            $user->ins_contact_id, 
            $user->delivery_notes
        );
    }

    public function getTotal()
    {   
        return $this->calculateSharedDiscount->getTotalPayment();
    }

    public function getTotalDiscount()
    {   
        return $this->calculateSharedDiscount->getTotalDiscount();
    }

    public function getUserDetails()
    {
        return $this->userDetails;
    }

    public function getProducts()
    {   
        $products = array();
        foreach($this->subscriptionsData as $row) {
            $products[] = (array)$row->getProduct();
        }

        $itemType = (int)env('PRODUCT_ITEMTYPE');
        $couponType = env('COUPON_ITEMTYPE');
        
        foreach($this->calculateSharedDiscount->getRecurringDiscount() as $collection) 
        {
            foreach($collection as $row)
            {
                $row = is_array($row) ? (object)$row : $row;
                $products[] = [
                    'infusionsoftProductId' => 0, 
                    'itemType' => $itemType, 
                    'price' => -$row->total, 
                    'quantity' => 1, 
                    'description' => 'Coupon: '. $row->code, 
                    'notes' => 'Coupon: '. $row->discountValue. ' '.$row->code.' '.$row->name,
                    'productType' => $couponType
                ];
            }
        }

        return $products;
    }

    public function getSubscriptions()
    {   
        return $this->subscriptionsData;
    }

    public function getForResumeSubscriptions()
    {   
        return $this->subscriptionsForResumeData;
    }
    
    private function calculateDiscountInit()
    {
        $this->calculateSharedDiscount = new CalculateSharedCouponDiscountDto($this->userId, $this->subscriptionsData);
    }    

}