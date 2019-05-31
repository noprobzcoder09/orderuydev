<?php

namespace App\Services\INFSBilling\Validator;

Class Invoice
{   

    public function validate(int $merchantId, string $cardId, int $contactId)
    {
        if (empty($merchantId)) {
            throw new \Exception(__('billing.noMerchant'), 1);
        }

        if (empty($cardId)) {
            throw new \Exception(__('billing.noCardId'), 1);
        }

        if (empty($contactId)) {
            throw new \Exception(__('billing.noContactId'), 1);
        }
    }
}

