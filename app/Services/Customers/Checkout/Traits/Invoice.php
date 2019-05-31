<?php

namespace App\Services\Customers\Checkout\Traits;

Trait Invoice
{   
    
    public function createInvoice()
    {   
        $this->invoice->store([
            'user_id' => $this->auth->getId(),
            'ins_invoice_id' => $this->billing->getInvoiceId(),
            'ins_order_id' => $this->billing->getOrderId(),
            'price' => $this->worker->getTotalThisWeek(),
            'status' => 'paid'
        ]);
    }

}


