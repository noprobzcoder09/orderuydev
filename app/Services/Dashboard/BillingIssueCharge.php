<?php

namespace App\Services\Dashboard;

use App\Services\Customers\BillingIssue\Providers\Billing;
use App\Services\Customers\BillingIssue\Providers\Card;
use App\Services\Customers\Account\Billing\UnpaidBilling;
use App\Services\Customers\Account\Billing\Data\Subscriptions;
use App\Services\Customers\Account\Billing\Data\SubscriptionsSelections;
use App\Services\Customers\Account\Billing\Data\Invoice;
use App\Services\Customers\BillingIssue\User;
use App\Services\Customers\Account\Billing\Data\User as BillingDataUser;
use App\Services\Customers\Account\InfusionsoftCustomer;

use App\Services\Customers\Account\Validator\Factory as FactoryValidator;
use \App\Services\Log;
use DB;

class BillingIssueCharge
{      
    public function __construct(int $userId)
    {
        $this->id = $userId;
        $this->user = new User($userId);
        $this->log = new Log('customer billing issue','logs/'.date('Y').'/'.date('m').'/system-'.date('Y-m-d').'.log');
    }

    public function handle()
    {   
        DB::beginTransaction();
        try 
        {   
            $user = new BillingDataUser;
            $unpaidBilling = new UnpaidBilling($this->id);

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
                $this->log->info('User #'.$this->id.' Charge invoice was skipped due to discount is greater than or equal to the total billing amount.');
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
                    $this->id, 
                    $billing->message()
                );

                throw new \Exception($billing->message(), 1);
            }

            $subscription->updateToPaid(
                $this->id, 
                $unpaidBilling->getSubscriptionsId()
            );

            $subscriptionSelections->updateToPaid(
                $this->id, 
                $billing->getInvoiceId(), 
                $unpaidBilling->getSubscriptionsId()
            );

            $user->resetBillingAttempt(
                $this->id
            );

            $invoice = new Invoice;
            $invoice->store(
                $this->id, 
                $billing->getOrderId(), 
                $billing->getInvoiceId(),
                $unpaidBilling->getTotal()
            );
            
            DB::commit();

            $infusionsoftCustomer = new InfusionsoftCustomer($this->id, 'inline');
            $infusionsoftCustomer->updateCustomerInfs();
            $infusionsoftCustomer->updateCustomerDeliveryDetailsInfs();
            $infusionsoftCustomer->activeMenuDelivery();

            return ['success' => true, 'message' => __('billing.billed')];
        }
        catch(\Exception $e) {

            throw $e;
            
            DB::rollback();
            return ['success' => false, 'message' => $e->getMessage()];
        } 
        
    }

}

