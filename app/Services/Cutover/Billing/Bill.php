<?php

namespace App\Services\Cutover\Billing;

use App\Services\Cutover\Billing\Extended\Manager as BillingManager;
use App\Services\InfusionsoftV2\InfusionsoftFactory;
use App\Services\Log;

Class Bill
{   
    private $message;
    private $invoiceId;
    private $orderId;
    private $skippedChargeInvoice = false;
    
    public function __construct()
    {
        $this->log = new Log('cutover','logs/'.date('Y').'/'.date('m').'/system-'.date('Y-m-d').'.log');
    }

    public function setCard($cardId)
    {
        $this->cardId = $cardId;
        return $this;
    }

    public function setContactId($contactId)
    {
        $this->contactId = $contactId;
        return $this;
    }

    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    public function setInvoiceId($invoiceId)
    {
        $this->invoiceId = $invoiceId;
        return $this;
    }

    public function getInvoiceId()
    {
        return $this->invoiceId;
    }

    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
        return $this;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function setProducts(array $products)
    {
        $this->products = $products;
        return $this;
    }

    public function setNotes($notes = '')
    {
        $this->notes = $notes;
        return $this;
    }

    public function setSkippedChargeInvoice($skippedChargeInvoice = false)
    {
        $this->skippedChargeInvoice = $skippedChargeInvoice;
        return $this;
    }

    public function pay()
    {   
        try 
        {   
            $this->initApi();
            $this->billing = new BillingManager (
                ENV('MERCHANT_ID'), 
                $this->cardId, 
                $this->contactId,
                $this->total,
                $this->api
            );
            
            $this->billing->setType('Credit Card');
            $this->billing->setDate(new \DateTime(date('Y-m-d')));    
            $this->billing->setNotes($this->notes);
            $this->billing->setDescription('Customer recurring order.');
            $this->billing->setProducts($this->products);
            $this->billing->setSkippedInvoice($this->skippedChargeInvoice);

            $status = $this->billing->pay();

            $this->setInvoiceId($this->billing->getInvoiceId());
            $this->setOrderId($this->billing->getOrderId());
                
            if ($status == 1) {
                return true;
            }
    
            $this->log->error([
                'message' => __('billing.failedToBilled'),
                'contactId' => $this->contactId,
                'cardId' => $this->cardId,
                'total' => $this->total,
                'products' => $this->products
            ]);
    
            return false;
        }

        catch (\Exception $e) {
            $this->message = $e->getMessage();
            $this->log->error([
                'message' => $e->getMessage(),
                'contactId' => $this->contactId,
                'cardId' => $this->cardId,
                'total' => $this->total,
                'products' => $this->products
            ]);
            return false;
        }
    }

    public function getMessage()
    {
        return $this->message;
    }

    protected function initApi()
    {
        $this->api = (new InfusionsoftFactory('oauth2'))->service();
    }
}
