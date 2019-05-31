<?php

namespace App\Services\Card;

use App\Services\Card\RulesValidator as CardValidator; 
use App\Services\Card\Validator\Validator; 

use  App\Services\Card\Validator\Rules;
use  App\Services\Card\Validator\DB;
use  App\Services\Card\Validator\INFS;

use App\Services\Card\Contracts\Card;
use App\Services\Card\Contracts\Gateway;

Class Manager
{   
    protected $card;
    protected $last4;
    protected $storeAnyway = false;
    
    public function __construct(Card $card, Gateway $gateway) {
        $this->card = $card;
        $this->gateway = $gateway;
    }

    public function store()
    {   
        $this->setCard();
        
        $validator = new Validator(new Rules($this->card));
        $validator->validate();

        
        // $validator = new Validator(new DB($this->user->getId(), $cardId));
        // $validator->validate();

        // Validate credit card important fields
        $validator = new Validator(new INFS($this->card, $this->gateway, $this->storeAnyway()));
        $validator->validate();
        
        $data = $this->refillEmptyFields(array(
            'BillAddress2'
        ), $this->card->get());
        
        $cardId = $this->gateway->store($data);
        
        $this->setId($cardId);
        $this->setLast4($this->card->getCardNumberLast4());
    }

    public function setId(int $id) 
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

    public function setForceStore(bool $storeAnyway)
    {
        $this->storeAnyway = $storeAnyway;
    }

    public function storeAnyway()
    {
        return $this->storeAnyway;
    }

    private function setCard()
    {
        $this->card->set([
            'ContactId' => $this->card->getContactId(),
            "NameOnCard" => $this->card->getName(),
            "CardNumber"  => $this->card->getCardNumber(),
            "ExpirationMonth"  => $this->card->getExpMonth(),
            "ExpirationYear"  => $this->card->getExpYear(),
            "FirstName"  => $this->card->getFirstName(),
            "LastName"  => $this->card->getLastName(),
            "CVV2"  => $this->card->getCVC(),
            'CardType' => $this->card->getType(),
            'BillName' => $this->card->getBillName(),
            'BillAddress1' => $this->card->getBillAddress1(),
            'BillAddress2' => $this->card->getBillAddress2(),
            'BillCity' => $this->card->getBillCity(),
            'BillState' => $this->card->getBillState(),
            'BillZip' => $this->card->getBillZip(),
            'BillCountry'  =>$this->card->getBillCountry(),
            'PhoneNumber' => $this->card->getPhoneNumber(),
            'Email' => $this->card->getEmail()
        ]);
    }

    public function refillEmptyFields(array $fieldsNeedToFill = array(), array $data = array())
    {
        foreach($fieldsNeedToFill as $field) {
            if (isset($data[$field]) || empty($data[$field])) {
                $data[$field] = "-";
            }
        }

        return $data;
    }
}
