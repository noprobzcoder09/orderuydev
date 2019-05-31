<?php

namespace App\Services\Card\Validator;

use App\Services\Validator as Validate;

use App\Services\Card\Contracts\Validator as ValidatorInterface;
use App\Services\Card\User;

class DB extends User implements ValidatorInterface
{   
    private $isValid = true;
    private $message = '';

    public function __construct(int $userId, string $cardId) {
        parent::__construct($userId);
        $this->id = $cardId;
    }

    public function validator()
    {
        $cardId = $this->getCardId();
        if (in_array($this->id, $cardId )) {
            $this->isValid = false;
            $this->message = __('card.card_exist');
        }
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
