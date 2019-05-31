<?php

namespace App\Services\Customers\Account\Billing\Dto;

Class InvoiceDto
{   
    public function __construct(string $status, $invoiceId)
    {
        $this->status = $status;
        $this->invoiceId = $invoiceId;
    }   

    public function getStatus()
    {
        return $this->status;
    }

    public function getInvoiceId()
    {
        return $this->invoiceId;
    }

}
