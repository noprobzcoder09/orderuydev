<?php

namespace App\Services\Card\Validator;

use App\Services\Validator as Validate;

use App\Services\Card\Contracts\Validator as ValidatorInterface;

class Rules extends Validate implements ValidatorInterface
{   
    public function __construct(\App\Services\Card\Contracts\Card $card)
    {
        $this->card = $card;
    }

    public function validator()
    {
        $this->validate([
            'card_number' => $this->card->getCardNumber(),
            'expiration_month' => $this->card->getExpMonth(),
            'expiration_year' => $this->card->getExpYear(),
            'card_cvc' => $this->card->getCVC(),
        ], [
            'card_number' => ['required', new \LVR\CreditCard\CardNumber],
            'expiration_month' => ['required', new \LVR\CreditCard\CardExpirationMonth($this->card->getExpYear())],
            'expiration_year' => ['required', new \LVR\CreditCard\CardExpirationYear($this->card->getExpMonth())],
            'card_cvc' => ['required', new \LVR\CreditCard\CardCvc($this->card->getCardNumber())]
        ]);
    }

    public function success()
    {
        return $this->isValid();
    }

    public function message()
    {
        return $this->filterError($this->getMessage());
    }
}
