<?php

namespace App\Services\Cutover\Billing;

use App\Services\Cutover\Billing\Bill;
use App\Services\Customers\Account\InfusionsoftCustomer;

use App\Services\Cutover\Dto\SubscriptionsBilling;
use App\Services\Customers\Account\Billing\Dto\UserDetailsDto;

use App\Services\Cutover\Traits\BilingRepositoryProvider;

use App\Services\Cutover\Data\User;
use App\Services\Cutover\Data\Subscriptions;
use App\Services\Cutover\Data\SubscriptionsSelections;
use App\Services\Cutover\Data\SubscriptionsInvoice;

Class Manager
{   
    use BilingRepositoryProvider;

    private $userDetails;
    private $subscriptions;
    private $isSuccess = true;
    private $skippedChargeInvoice = false;

    const BILLING_ISSUE_STATUS = 'billing issue';
    const UNPAID_STATUS = 'unpaid';
    const PAID_STATUS = 'paid';
    const PAUSED_STATUS = 'paused';
    const ACTIVE_STATUS = 'active';

    public function __construct(
        UserDetailsDto $userDetails, 
        SubscriptionsBilling $subscriptions, 
        bool $skippedChargeInvoice
    ) {
        
        $this->userDetails = $userDetails;
        $this->subscriptionsData = $subscriptions;
        $this->skippedChargeInvoice = $skippedChargeInvoice;

        $this->user = new User;
        $this->subscriptions = new Subscriptions;
        $this->selections = new SubscriptionsSelections;
        $this->invoice = new SubscriptionsInvoice;
    }

    public function handle()
    {   
       
        $bill = new Bill;
        
        $status = $bill
        ->setCard($this->userDetails->getCardId())
        ->setContactId($this->userDetails->getContactId())
        ->setNotes($this->userDetails->getNotes())
        ->setProducts($this->subscriptionsData->getProducts())
        ->setTotal($this->subscriptionsData->getTotal())
        ->setSkippedChargeInvoice($this->skippedChargeInvoice)
        ->pay();
        
        $status_desc = $status == 1 ? self::PAID_STATUS : self::UNPAID_STATUS;
        foreach($this->subscriptionsData->getSubscriptions() as $row) {
            $this->updateSubscriptionStatus(
                $row->getSubscriptionId(),
                $row->getSubscriptionCycleId(), 
                $status_desc
            );

            $this->updateSubscriptionCycleInvoiceId(
                $row->getSubscriptionCycleId(), $bill->getInvoiceId()
            );
        }

        if (!empty($bill->getInvoiceId()) && !is_null($bill->getInvoiceId()))
        {
            $this->storeInvoice(
                $this->userDetails->getUserId(), 
                $bill->getInvoiceId(), 
                $bill->getOrderId(),
                $this->subscriptionsData->getTotal(),
                $status_desc
            );
        }

        if ($status_desc == self::UNPAID_STATUS) {
            $this->isSuccess = false;
            $this->updateBillingAttempt(
                $this->userDetails->getUserId(),
                $bill->getMessage()
            ); 
        } else {
            $this->resetBillingAttempt($this->userDetails->getUserId());
        }

        $infusionsoftCustomer = new InfusionsoftCustomer($this->userDetails->getUserId());
        $infusionsoftCustomer->updateCustomerDeliveryDetailsInfs();
        $infusionsoftCustomer->updateCustomerInfs(
            $status_desc == self::UNPAID_STATUS ? self::BILLING_ISSUE_STATUS : ''
        );

        echo $this->userDetails->getUserId().'\/'.($status_desc == self::UNPAID_STATUS ? self::BILLING_ISSUE_STATUS : '');
        echo "<br>";
    }

    public function isSuccess() 
    {
        return $this->isSuccess;
    }

}
