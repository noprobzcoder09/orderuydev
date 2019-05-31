<?php

namespace App\Services\INFSBilling;

use App\Services\INFSBilling\Validator\OrderItems;
use App\Services\INFSBilling\Validator\Invoice;
use App\Services\INFSBilling\Validator\Billing;

Class Validator
{   

    public function __construct()
    {
        $this->items = new OrderItems;
        $this->invoice = new Invoice;
        $this->billing = new Billing;
    }

    public function orderItems(array $products)
    {
        if (count($products) > 0) {
            foreach($products as $row) {
                $this->items->validate($row);
            }
        } else {
            $this->items->validate($products);
        }
    }

    public function invoice(int $merchantId, string $cardId, int $contactId)
    {
        $this->invoice->validate($merchantId, $cardId, $contactId);
    }

    public function billing(float $amount)
    {
        $this->billing->validate($amount);
    }

}

