<?php

namespace App\Services\Customers\Subscriptions\Extended;

use App\Repository\UsersRepository;
use App\Services\Manageplan\Auth as AuthParent;

Class Auth extends AuthParent
{       
    public function set(int $userId)
    {
        $this->repo = new UsersRepository;
        $this->repo->setRow($userId);
        $this->setId($userId);
    }

    public function getContactId()
    {
        return $this->repo->getContactId();
    }

    public function getDeliveryNotes()
    {
        return $this->repo->getDeliveryNotes();
    }
        
}
