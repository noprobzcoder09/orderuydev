<?php

namespace App\Services\INFSBilling\Traits;

Trait SettersGetters
{   
    public $products = array();
    public $date;
    public $description;
    public $comission = false;
    public $price;
    private $invoiceId = 0;
    private $orderId = 0;
    private $skippedInvoice = false;

    public function setProducts(array $products)
    {
        $this->products = $products;
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function setDate(\DateTime $dateTime)
    {
        $this->date = $dateTime;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setComission(bool $comission)
    {
        $this->comission = $comission;
    }

    public function getComission()
    {
        return $this->comission;
    }

    public function setMerchantId(int $merchantId)
    {
        $this->merchantId = $merchantId;
    }

    public function getMerchantId()
    {
        return $this->merchantId;
    }

    public function setContactId(string $contactId)
    {
        $this->contactId = $contactId;
    }

    public function getContactId()
    {
        return $this->contactId;
    }

    public function setCardId(int $cardId)
    {
        $this->cardId = $cardId;
    }

    public function getCardId()
    {
        return $this->cardId;
    }

    public function setAmount(float $amount)
    {
        $this->amount = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setType(string $type = 'Credit Card')
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setNotes($notes = 'Invoice Notes')
    {
        $this->notes = empty($notes) ? 'Invoice Notes' : $notes;
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function setInvoiceId(int $id)
    {
        $this->invoiceId = $id;
    }

    public function getInvoiceId()
    {
        return $this->invoiceId;
    }

    public function setOrderId(int $id)
    {
        $this->orderId = $id;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function setSkippedInvoice($skipped = false)
    {
        return $this->skippedInvoice = $skipped;
    }

    public function isSkippedInvoice()
    {
        return $this->skippedInvoice;
    }
    
    
}   

    
