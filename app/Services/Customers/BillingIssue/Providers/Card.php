<?php

namespace App\Services\Customers\BillingIssue\Providers;

use App\Services\Card\Manager;
use App\Services\Card\Card as CardAdapter;
use App\Services\Card\INFS;

use App\Services\Customers\BillingIssue\User;
use App\Services\Customers\BillingIssue\Extended\Request;

Class Card
{   
    public function __construct(int $userId)
    {
        $this->user = new User($userId);
        $this->request = new Request;
    }

    public function createNewCard()
    {       
        $this->card = new CardAdapter;
        $this->infs = new INFS;

        $this->setCard();
        
        $this->card = new Manager($this->card, $this->infs);

        if (strtolower(env('APP_ENV')) == 'test') {
            $this->card->setId((int)date('Yis'));
            return;
        }
        $this->card->setForceStore(true);
        $this->card->store();

        $this->setId($this->card->getId());
        $this->setLast4($this->card->getLast4());
    }

    private function setCard()
    {
        $this->card->setCardNumber($this->request->getCardNumber());
        $this->card->setExpMonth($this->request->getExpMonth());
        $this->card->setExpYear($this->request->getExpYear());
        $this->card->setCVC($this->request->getCardCVC());
        $this->card->setType($this->request->getCardNumber());
        $this->card->setName($this->request->getCardName());
        $this->card->setFirstName($this->user->getFirstName());
        $this->card->setLastName($this->user->getLastName());
        $this->card->setBillName($this->user->getBillName());
        $this->card->setBillAddress1($this->user->getBillAddress1());
        $this->card->setBillAddress2($this->user->getBillAddress2());
        $this->card->setBillCity($this->user->getBillCity());
        $this->card->setBillState($this->user->getBillState());
        $this->card->setBillZip($this->user->getBillZip());
        $this->card->setBillCountry($this->user->getBillCountry());
        $this->card->setPhoneNumber($this->user->getPhoneNumber());
        $this->card->setEmail($this->user->getEmail());
        $this->card->setContactId($this->user->getContactId());
    }

    public function getSavedCards()
    {   
        $contactId = $this->user->getContactId();
        $list = $this->user->getSavedCardId();
        
        if (empty($contactId)) {
            return [];
        }
        $last4 = array();
        foreach($list as $row) {
            $row = is_array($row) ? (object)$row : $row; 
            $cards[] = array(
                'id' => $row->id,
                'last4' => $row->last4,
                'default' => $this->user->getCardDefault() == $row->id
            );
        }
        
        return $cards;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setLast4($last4)
    {
        $this->last4 = $last4;
    }

    public function getLast4()
    {
        return $this->last4;
    }

}


