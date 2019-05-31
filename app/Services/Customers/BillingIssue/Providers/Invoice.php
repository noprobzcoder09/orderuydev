<?php

namespace App\Services\Customers\BillingIssue\Providers;

use App\Repository\SubscriptionInvoiceRepository;

Class Invoice
{  
    public function __construct()
    {
        $this->repo = new SubscriptionInvoiceRepository;
    }

    public function store(int $userId, $orderId, $invoiceID, $status = 'paid')
    {
        $invoice = $this->repo->getRaw($userId, $orderId, $invoiceID);
        
        if (empty($invoice->id)) {
            $this->repo->store(array(
                'user_id' => $userId,
                'ins_order_id' => $orderId,
                'ins_invoice_id' => $invoiceID,
                'status' => $status
            ));
        } else {
            $this->repo->update(array(
                'id' => $invoice->id,
                'user_id' => $userId,
                'ins_order_id' => $orderId,
                'ins_invoice_id' => $invoiceID,
                'status' => $status
            ));
        }
    }
}
