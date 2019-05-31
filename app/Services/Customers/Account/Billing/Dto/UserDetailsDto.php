<?php

namespace App\Services\Customers\Account\Billing\Dto;

use App\Services\Customers\Account\Billing\CardID;

Class UserDetailsDto
{   

    public function __construct(
        int $userId, 
        CardID $cardId, 
        $contactId, 
        $notes = ''
    ) {
        $this->userId = $userId;
        $this->cardId = $cardId;
        $this->contactId = $contactId;
        $this->notes = $notes;
    }

    public function getContactId()
    {
        return $this->contactId;
    }

     public function getUserId()
    {
        return $this->userId;
    }

    public function getCardId()
    {
        return $this->cardId->getCarId();
    }

    public function getNotes()
    {
        return $this->notes;
    }

}
