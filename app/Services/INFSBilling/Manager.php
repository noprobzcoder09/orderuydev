<?php

namespace App\Services\INFSBilling;

use App\Services\INFSBilling\Bill;
use App\Services\INFSBilling\Invoice;
use App\Services\INFSBilling\Order;
use App\Services\INFSBilling\OrderItems;
use App\Services\INFSBilling\Validator;

use App\Services\INFSBilling\Traits\SettersGetters;
use App\Services\Log;

Class Manager
{   
    use SettersGetters;

    public function __construct(int $merchantId, int $cardId, string $contactId, float $amount, $api = null)
    {   
        $this->setMerchantId($merchantId);
        $this->setCardId($cardId);
        $this->setContactId($contactId);
        $this->setAmount($amount);

        $this->api = $api;
        $this->log = new Log('billing','logs/'.date('Y').'/'.date('m').'/system-'.date('Y-m-d').'.log');
    }

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
                $invoice->getId(), 
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

    public function validator()
    {
        $validate = new Validator;

        $validate->orderItems($this->getProducts());

        $validate->invoice($this->getMerchantId(), $this->getCardId(), $this->getContactId());

        // $validate->billing($this->getAmount());
    }
    
}   

    
