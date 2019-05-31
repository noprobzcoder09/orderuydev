<?php

namespace App\Services\Customers\Checkout\Traits;

use App\Services\Card\Manager;
use App\Services\Card\Card as CardAdapter;
use App\Services\Card\INFS;

use Request;
use Auth;


Trait Card
{   
 
    public function createCard()
    {       
        $this->card = new CardAdapter;
        $this->infs = new INFS($this->api);
        
        $cardManager = new Manager($this->card, $this->infs);
        
        if (!$this->request->isCardNew()) {
            $this->card = $cardManager;
            $this->card->setId($this->request->getCard());
            $this->card->setLast4($this->request->getCardLast4());
            return;
        }

        $this->setCard();
        $cardManager->setForceStore(true);
        $cardManager->store();

        $this->card = $cardManager;
    }

    private function setCard()
    {
        $this->card->setCardNumber($this->request->getCardNumber());
        $this->card->setExpMonth($this->request->getExpMonth());
        $this->card->setExpYear($this->request->getExpYear());
        $this->card->setCVC($this->request->getCardCVC());
        $this->card->setType($this->request->getCardNumber());
        $this->card->setName($this->request->getCardName());
        $this->card->setFirstName($this->request->getFirstName());
        $this->card->setLastName($this->request->getLastName());
        $this->card->setBillName($this->request->getBillName());
        $this->card->setBillAddress1($this->request->getBillAddress1());
        $this->card->setBillAddress2(empty($this->request->getBillAddress2()) ? "&nbsp" : $this->request->getBillAddress2());
        $this->card->setBillCity($this->request->getBillCity());
        $this->card->setBillState($this->request->getBillState());
        $this->card->setBillZip($this->request->getBillZip());
        $this->card->setBillCountry($this->request->getBillCountry());
        $this->card->setPhoneNumber($this->request->getPhoneNumber());
        $this->card->setEmail($this->request->getEmail());
        $this->card->setContactId($this->contact->getId());
    }

}


