<?php

namespace App\Services\Cutover\Billing\Extended;

use App\Services\INFSBilling\Bill;
use App\Services\INFSBilling\Invoice;
use App\Services\INFSBilling\Order;
use App\Services\INFSBilling\OrderItems;
use App\Services\INFSBilling\Manager as BillingManager;

Class Manager extends BillingManager
{   
    public function pay(): bool
    {       
        $this->validator();        

        $order = new Order(
            $this->getContactId(), 
            $this->getDate(), 
            $this->getDescription(),
            $this->api
        );

        $this->log->info("Customer Payment Info:  ", array(
            'contactId' => $this->getContactId(),
            'cardId' => $this->getCardId(),
            'orderId' => $order->getId(),
            'amount' => $this->getAmount()
        ));
        
        $this->setOrderId($order->getId());
        
        $items = new OrderItems($order->getId(), $this->getProducts(), $this->api);

        $invoice = new Invoice($order->getId(), $this->api);

        $this->setInvoiceId($invoice->getId());

        if (!$this->isSkippedInvoice()) 
        {   
            $invoice->create(
                $this->getMerchantId(),
                $this->getCardId(), 
                $this->getNotes(), 
                $this->getComission()
            );

            $billing = new Bill(
                $this->getInvoiceId(), 
                $this->getAmount(),
                $this->getDate(),
                $this->getType(),
                $this->getNotes(),
                $this->api
            );

            return $billing->success();
        }

        return true;
    }

}
