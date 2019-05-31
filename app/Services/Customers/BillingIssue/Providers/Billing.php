<?php

namespace App\Services\Customers\BillingIssue\Providers;

use App\Services\Customers\BillingIssue\Extended\BillingManager;
use App\Services\Customers\Subscriptions\Extended\Auth;
use App\Services\InfusionsoftV2\InfusionsoftFactory;

Class Billing
{   
    private $cardId;
    private $contactId;
    private $total;
    private $products;
    private $success = false;
    private $message;
    private $invoiceId;
    private $orderId;
    private $skippedChargeInvoice = false;

    public function __construct(
        $cardId, 
        $contactId, 
        $invoiceId = '',
        $orderId = '',
        $products = '', 
        $total, 
        $notes = '',
        bool $skippedChargeInvoice
    ) {   
        $this->cardId = $cardId;
        $this->contactId = $contactId;
        $this->total = $total;
        $this->products = $products;
        $this->notes = $notes;
        $this->orderId = $orderId;
        $this->invoiceId = $invoiceId;

        $this->skippedChargeInvoice = $skippedChargeInvoice;

        $this->bill();
    }

    public function bill()
    {       
        try
        {   
            $this->initApi();
            $this->billing = new BillingManager(
                env('MERCHANT_ID'), 
                $this->cardId, 
                $this->contactId,
                $this->total,
                $this->api
            );

            $this->billing->setType('Credit Card');
            $this->billing->setDate(new \DateTime(date('Y-m-d')));    
            $this->billing->setNotes($this->notes);
            $this->billing->setDescription('Customer order.');
            $this->billing->setProducts($this->products);
            $this->billing->setSkippedInvoice($this->skippedChargeInvoice);
            
            if (!empty($this->invoiceId)) {
                $this->billing->setInvoiceId($this->invoiceId);
            }
    
            if (!empty($this->orderId)) {
                $this->billing->setOrderId($this->orderId);
            }
            
            $this->success = $this->billing->pay();
        }
        catch(\Exception $e)
        {
            $this->success = false;
            $this->message = $e->getMessage();
        }
    }

    public function getInvoiceId()
    {
        return $this->billing->getInvoiceId();
    }

    public function getOrderId()
    {
        return $this->billing->getOrderId();
    }

    public function success()
    {
        return $this->success;
    }

    public function message()
    {
        return $this->message;
    }

    protected function initApi()
    {
        $this->api = (new InfusionsoftFactory('oauth2'))->service();
    }
}


