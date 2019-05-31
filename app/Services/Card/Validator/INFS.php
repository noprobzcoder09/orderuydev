<?php

namespace App\Services\Card\Validator;

use App\Services\Validator as Validate;

use App\Services\Card\Contracts\Validator as ValidatorInterface;
use  App\Services\Card\Validator\DB;

class INFS implements ValidatorInterface
{   
    private $isValid = true;
    private $storeAnyway = false;
    private $message = '';

    public function __construct(
        \App\Services\Card\Contracts\Card $card,
        \App\Services\Card\Contracts\Gateway $gateway,
        bool $storeAnyway = false
    )
    {
        $this->card = $card;
        $this->gateway = $gateway;
        $this->storeAnyway = $storeAnyway;
    }

    public function validator()
    {   
        $this->validateImportantFields($this->card->get());
    }

    private function validateImportantFields($card)
    {
        $important = [];
        $data = [];
        
        foreach($this->gateway->cardFields as $key) {
            if (!in_array($key, array_keys($card))) {
                $important[] = $key;
                continue;
            }
            if (empty($card[$key])) {
                $important[] = $key;
                continue;
            }
            $data[$key] = $card[$key];
        }
        
        if (!empty($important)) {
            throw new \Exception(__('Credit Card Billing Required fields are empty '.implode(', ', $important).'.'), 1);
        }
        
        if (!$this->storeAnyway) {
            $cardId = $this->gateway->getId(
                $card['ContactId'],
                $card['CardNumber'],
                $card['ExpirationMonth'],
                $card['ExpirationYear']
            );

            if ($cardId > 0) {
                throw new \Exception(__('card.card_exist'), 1);
            }
        }
        
        return $data;
    }

    public function success()
    {
        return $this->isValid;
    }

    public function message()
    {
        return $this->message;
    }
}
