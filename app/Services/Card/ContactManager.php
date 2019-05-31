<?php

namespace App\Services\Card;

use App\Services\Card\Contact;
use App\Services\Card\Validator\Validator; 
use App\Services\Card\Validator\Contact as ContactValidator;

Class ContactManager
{   
    
    public function __construct(array $data, $api = null)
    {
        $this->contact = new Contact($api);
        $this->data = $data;
    }

    public function store()
    {       
        // Validate credit card important fields
        $validator = new Validator(new ContactValidator($this->contact, $this->data));
        $validator->validate();

        $this->data = $this->refillEmptyFields(array(
            'StreetAddress2',
            'Phone1'
        ), $this->data);

        
        $this->contact->create($this->data);

        $this->setId($this->contact->getId());
    }

    public function update(int $contactId)
    {       
        // Validate credit card important fields
        $object = new ContactValidator($this->contact, $this->data, $contactId);
        $object->update = true;
        $validator = new Validator($object);
        $validator->validate();

        $this->data = $this->refillEmptyFields(array(
            'StreetAddress2',
            'Phone1'
        ), $this->data);

        $this->contact->update($contactId, $this->data);

        $this->setId($this->contact->getId());
    }

    public function optInEmail(string $email, string $optInReason = 'Opted In as per request') 
    {
        $this->contact->optInEmail(
            $email,
            $optInReason
        );
    }

    public function setId(int $id) 
    {
        $this->id = $id;
    }

    public function getId() 
    {
        return $this->id;
    }

    public function refillEmptyFields(array $fieldsNeedToFill = array(), array $data = array())
    {
        foreach($fieldsNeedToFill as $field) {
            if (empty($data[$field])) {
                $data[$field] = " ";
            }
        }

        return $data;
    }
}
