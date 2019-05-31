<?php

namespace App\Services\Cutover\Dto;


Class UserDetails
{   

    public function __construct($userId, $cardId, $contactId, $notes = '') {
        $this->userId = $userId;
        $this->cardId = $cardId;
        $this->contactId = $contactId;
        $this->notes = $notes;
    }

    public function getContactId()
    {
        return $this->contactId;
    }

    public function getCardId()
    {
        return $this->cardId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getNotes()
    {
        return $this->notes;
    }

}
