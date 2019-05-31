<?php

namespace App\Services\Customers\BillingIssue;

use App\Services\Customers\BillingIssue\Providers\Billing;
use App\Services\Customers\BillingIssue\Providers\Card;
use App\Services\Customers\Account\Billing\UnpaidBilling;
use App\Services\Customers\Account\Billing\Data\Subscriptions;
use App\Services\Customers\Account\Billing\Data\SubscriptionsSelections;
use App\Services\Customers\Account\Billing\Data\Invoice;
use App\Services\Customers\Account\InfusionsoftCustomer;
use App\Services\Customers\Account\Billing\Data\User as BillingDataUser;
use App\Services\Customers\Account\Validator\Factory as FactoryValidator;
use App\Repository\SubscriptionRepository;
use App\Repository\SubscriptionSelectionsRepository;
use DB;

use App\Traits\Auditable;


Trait Provider
{   

    use Auditable;

    protected function createNewCreditCard(int $userId)
    {   
        try 
        {
            (new FactoryValidator($userId))->account();

            $this->user = new User($userId);
            
            $this->card = new Card($userId);
            $this->card->createNewCard();

            $this->user->storeCardId($this->card->getId(), $this->card->getLast4());
            $this->user->updateCardDefault($userId, $this->card->getId());


            $description        = "Added new credit card for {$this->user->getBillName()}";
            $this->audit($title = 'Admin Added New Credit Card', $description, $additional_data = '');            

            return $this->cardContent($userId);
        }
        catch(\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    protected function updateCardDefault($userId)
    {   
        $this->user = new User($userId);

        $description        = "Updated new credit card of {$this->user->getBillName()}";
        $this->audit($title = 'Admin Updated Credit Card', $description, $additional_data = '');  

        return (int)$this->user->updateCardDefault($userId, $this->request->getCardId());
    }

    protected function subscriptionBillNow($userId)
    {   
        DB::beginTransaction();
        try 
        {
            $this->user = new User($userId);
            $unpaidBilling = new UnpaidBilling($userId);
            $user = new BillingDataUser;

            $subscription = new Subscriptions;
            $subscriptionSelections = new SubscriptionsSelections;

            // Skip recharge invoice when the tota discount
            // is greater than the total amount
            $skippedChargeInvoice = false;
            if (
                ($unpaidBilling->getTotalDiscount() > 0)
                && ($unpaidBilling->getTotalDiscount() >= $unpaidBilling->getTotal())
            ) {
                $skippedChargeInvoice = true;
                $this->log->info('User #'.$userId.' Charge invoice was skipped due to discount is greater than or equal to the total billing amount.');
            }

            $billing = new Billing (
                $this->user->getCardId(), 
                $this->user->getContactId(),
                null,
                null,
                $unpaidBilling->getProducts(),
                $unpaidBilling->getTotal(),
                $this->user->getDeliveryNotes(),
                $skippedChargeInvoice
            );
            
            if (!$billing->success()) {
                $user->updateBillingAttempt(
                    $userId, 
                    $billing->message()
                );

                throw new \Exception($billing->message(), 1);
            }

            $formatted_billing_amount = number_format($unpaidBilling->getTotal(), 2);
            $description        = "Created a new billing for {$this->user->getBillName()} with the total billing amount of ({$formatted_billing_amount})";
            $this->audit($title = 'Admin Bill Now', $description, $additional_data = '');  


            $subscription->updateToPaid(
                $userId, 
                $unpaidBilling->getSubscriptionsId()
            );

            $subscriptionSelections->updateToPaid(
                $userId, 
                $billing->getInvoiceId(), 
                $unpaidBilling->getSubscriptionsId()
            );

            $user->resetBillingAttempt(
                $userId
            );

            $invoice = new Invoice;
            $invoice->store(
                $userId, 
                $billing->getOrderId(), 
                $billing->getInvoiceId(),
                $unpaidBilling->getTotal()
            );

            DB::commit();

            $infusionsoftCustomer = new InfusionsoftCustomer($userId, 'inline');
            $infusionsoftCustomer->updateCustomerInfs();
            $infusionsoftCustomer->updateCustomerDeliveryDetailsInfs();
            $infusionsoftCustomer->activeMenuDelivery();

            $description        = "Created an Invoice for {$this->user->getBillName()}: Invoice ID is {$billing->getInvoiceId()}";
            $this->audit($title = 'Admin Created an Invoice', $description, $additional_data = ''); 

            return ['success' => true, 'message' => __('billing.billed')];
        }
        catch(\Exception $e) {
            DB::rollback();
            return ['success' => false, 'message' => $e->getMessage()];
        } 
    }

    public function cancelForTheWeek(int $userId)
    {   
        try 
        {
            if (empty($this->request->getCycleId())) {
                throw new \Exception(__('Cycle is required.'), 1);
            }

            if (empty($userId)) {
                throw new \Exception(__('Customer is required.'), 1);
            }

            $unpaidSubscriptionSelections = $this->data->getUnpaidSubscriptionCyclesByCustomerAndCycleId(
                $userId,
                $this->request->getCycleId()
            );
            
            $unpaidSubscriptionSelectionsId = array();
            $unpaidSubscriptionId = array();
            foreach($unpaidSubscriptionSelections as $row) {
                array_push($unpaidSubscriptionId, $row->subscription_id);
                array_push($unpaidSubscriptionSelectionsId, $row->id);
            }

            if (count($unpaidSubscriptionSelectionsId) <= 0) {
                 throw new \Exception(__('There is no customer unpaid subscription.'), 1);
            }

            $subscriptionSelections = new SubscriptionsSelections;
            $subscriptionSelections->cancelForTheWeek(
                $userId, 
                $unpaidSubscriptionSelectionsId
            );

            $subscriptions = new Subscriptions;
            $subscriptions->updateToPaid($userId, $unpaidSubscriptionId);

            $infusionsoftCustomer = new InfusionsoftCustomer($userId, 'inline');
            $infusionsoftCustomer->updateCustomerInfs();
            $infusionsoftCustomer->updateCustomerDeliveryDetailsInfs();
            $infusionsoftCustomer->cancelledWeek();

            $this->user = new User($userId);
            $description        = "Cancelled the subscription for {$this->user->getBillName()} - Unpaid Subscription IDs: [{$unpaidSubscriptionId}]";
            $this->audit($title = 'Admin Cancelled Subscription', $description, $additional_data = ''); 

            return ['success' => true, 'message' => __('Successfully cancelled subscription.')];
        }
        catch(\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        } 
    }

    public function cancelSubscription(int $userId)
    {   
        try 
        {
            if (empty($userId)) {
                throw new \Exception(__('Customer id is required.'), 1);
                
            }
            $subscriptionRepo = new SubscriptionRepository;
            $subscriptionSelectionRepo = new SubscriptionSelectionsRepository;
            foreach($this->customerRepo->getUnpaidSubscriptions($userId) as $row) {
                $subscriptionRepo->cancellPlan($userId, $row->subscription_id);
                $subscriptionSelectionRepo->cancellPlanCurrentWeek($userId, $row->subscription_id);
            }
            
            $infusionsoftCustomer = new InfusionsoftCustomer($userId, 'inline');
            $infusionsoftCustomer->updateCustomerInfs();
            $infusionsoftCustomer->updateCustomerDeliveryDetailsInfs();
            $infusionsoftCustomer->cancelledWeek();

            $this->user = new User($userId);
            $description        = "Cancelled the Customer {$this->user->getBillName()}";
            $this->audit($title = 'Admin Cancelled The Customer', $description, $additional_data = '');             

            return ['success' => true, 'message' => __('Successfully cancelled subscription.')];
        }
        catch(\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        } 
    }
    

    public function cardList(int $userId)
    {
        $this->card = new Card($userId);
        return $this->card->getSavedCards();
    }

    private function filterProducts($products)
    {
        $data = [];
        foreach($products as $row) {
            $row = is_array($row) ? (object)$row : $row;
            $data[] = [
                'infusionsoftProductId' => $row->infusion_product_id, 
                'itemType' => $row->item_type ?? (int)env('PRODUCT_ITEMTYPE'), 
                'price' => $row->price, 
                'quantity' => $row->quantity, 
                'description' => $row->name, 
                'notes' => $row->notes ?? ''
            ];
        }
        return $data;
    }

}


