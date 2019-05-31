<?php

namespace App\Services\Customers\Checkout\Traits;

use Auth;

Trait User
{   
    public function createUser()
    {   
        $data = $this->request->getUser();  
        if (!$this->user->new()) {
            $this->user->update(Auth::id(), $data);
        }
        else {
            $this->user->store($data);
        }

        $this->auth->setId($this->user->getId());
        $this->auth->set($this->user->getId());
    }
    
    public function updateUserCardAndContactId()
    {   
        $this->user->updateContactId($this->auth->getId(), $this->contact->getId());

        if ($this->request->isCardNew()) {
            $this->user->storeCardId($this->card->getId(), $this->card->getLast4());
        }
    }

    public function login()
    {
        if ($this->user->new() && !Auth::check()) {
            Auth::loginUsingId($this->auth->getId());
        }
    }

    public function setUserStatus()
    {
        $this->user->setNew(true);
        if (Auth::check()) {
            $this->user->setNew(false);
            $this->user->setId(Auth::id());
        }
    }

    public function createAccountWithoutName()
    {
        $this->user->createLoginAccountWithoutName(
            $this->request->getEmail(),
            $this->request->getPassword(),
            $this->contact->getId()
        );

        $this->auth->setId($this->user->getId());
        $this->auth->set($this->user->getId());

        $this->setUserStatus();

    }
}


