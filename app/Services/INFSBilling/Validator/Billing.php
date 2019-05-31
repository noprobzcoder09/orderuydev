<?php

namespace App\Services\INFSBilling\Validator;

Class Billing
{   

    public function validate(float $amount)
    {
        if ($amount <= 0) {
            throw new \Exception(__('billing.noOrderAmountZero'), 1);
        }
        
        if (empty($amount)) {
            throw new \Exception(__('billing.noOrderAmount'), 1);
        }
    }
}

