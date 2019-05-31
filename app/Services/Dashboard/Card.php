<?php

namespace App\Services\Dashboard;

use App\Services\Card\Manager;
use App\Services\Card\Card as CardAdapter;
use App\Services\Card\INFS;

use App\Services\Card\User as UserModel;
use App\Services\Card\Traits\Card as TraitCard;

class Card
{      
    use TraitCard;

    public function __construct(int $userId)
    {
        $this->id = $userId;
    }

    public function create()
    {
        try 
        {   
            $this->card = new CardAdapter;
            $this->user = new UserModel($this->id);
            $this->infs = new INFS;

            $this->setCard();
            
            $manager = new Manager($this->card, $this->infs);

            $manager->setForceStore(true);
            $manager->store();

            $this->user->storeCardId($manager->getId(), $manager->getLast4());

            return ['success' => true, 'message' => sprintf(__('crud.created'),'card')];
        }
        catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function createAndUpdateDefaultCard()
    {
        try 
        {   
            $this->card = new CardAdapter;
            $this->user = new UserModel($this->id);
            $this->infs = new INFS;

            $this->setCard();
            
            $manager = new Manager($this->card, $this->infs);

            $manager->setForceStore(true);
            $manager->store();

            $this->user->storeAndUpdateDefaultCardId($manager->getId(), $manager->getLast4());

            return ['success' => true, 'message' => sprintf(__('crud.created'),'card')];
        }
        catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function setCard()
    {
        $this->card->setCardNumber($this->getCardNumber());
        $this->card->setExpMonth($this->getExpMonth());
        $this->card->setExpYear($this->getExpYear());
        $this->card->setCVC($this->getCardCVC());
        $this->card->setType($this->getCardNumber());
        $this->card->setName($this->getCardName());
        $this->card->setFirstName($this->getFirstName());
        $this->card->setLastName($this->getLastName());
        $this->card->setBillName($this->getBillName());
        $this->card->setBillAddress1($this->getBillAddress1());
        $this->card->setBillAddress2($this->getBillAddress2());
        $this->card->setBillCity($this->getBillCity());
        $this->card->setBillState($this->getBillState());
        $this->card->setBillZip($this->getBillZip());
        $this->card->setBillCountry($this->getBillCountry());
        $this->card->setPhoneNumber($this->getPhoneNumber());
        $this->card->setEmail($this->getEmail());
        $this->card->setContactId($this->user->getContactId());
    }
}

