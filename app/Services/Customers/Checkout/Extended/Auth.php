<?php

namespace App\Services\Customers\Checkout\Extended;

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

    public function getDeliveryNotes()
    {
        return $this->repo->getDeliveryNotes();
    }

}
