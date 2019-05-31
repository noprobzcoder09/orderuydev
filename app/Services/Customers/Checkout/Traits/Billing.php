<?php

namespace App\Services\Customers\Checkout\Traits;

use App\Services\INFSBilling\Manager as BillingManager;

Trait Billing
{   
 
    public function createBilling()
    {       
        $this->billing = new BillingManager (
            env('MERCHANT_ID'), 
            $this->card->getId(), 
            $this->contact->getId(),
            $this->worker->getTotalThisWeek(),
            $this->api
        );

        if (strtolower(env('APP_ENV')) == 'test') {
            $this->billing->setInvoiceId((int)date('Yis'));
            $this->billing->setOrderId((int)date('Yis'));

            return;  
        }
        
        $this->billing->setType('Credit Card');
        $this->billing->setDate(new \DateTime('now'));    
        $this->billing->setNotes($this->request->getDeliveryNotes());
        $this->billing->setDescription('Customer order.');
        $this->billing->setProducts($this->getProducts());
        
        if (
            ($this->worker->total() > 0)
            && ($this->worker->getTotalDiscount() > 0)
            && ($this->worker->getTotalDiscount() >= $this->worker->total())
        ) {
            $this->log->info('Charged invoice was skipped due to discount is greater than or equal to the total billing amount.');
            $this->billing->setSkippedInvoice(true);
        }   
        
        if(!$this->billing->pay()) {
            throw new \Exception(sprintf(__('billing.failedToBilled'),'subscription. Please try again'), 1);
            
        }
    }

}


