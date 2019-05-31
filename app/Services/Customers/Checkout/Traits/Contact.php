<?php

namespace App\Services\Customers\Checkout\Traits;

use App\Services\Card\ContactManager;
use App\Services\InfusionsoftV2\InfusionsoftFactory;
use App\Repository\ZTRepository;

Trait Contact
{   
 
    public function createContact(array $contact)
    {       
        $this->contact = new ContactManager($contact, $this->api);
        // Search contact email
        // Store/Update contact infusionsoft record
        $infusionsoftContactId = $this->user->getContactIdByEmail(
            $this->request->getEmail()
        );

        if (empty($this->api)) {
            $api = (new InfusionsoftFactory('oauth2'))->service();    
        } else {
            $api = $this->api;
        }
        
        
        if(empty($infusionsoftContactId))
        {
            $infusionsoftData = $api->fetchContactByEmail(
                $this->request->getEmail(), array('Id')
            );
            $infusionsoftContactId = $infusionsoftData[0]['Id'] ?? 0;
        }
        
        if (empty($infusionsoftContactId)) {
            $this->contact->store();
        } else {
            $this->contact->update(
                $infusionsoftContactId
            );
        }

        // Optin email for marketing purpose
        $api->optIn($this->request->getEmail(), 'opted in via website checkout');
    }

    private function getContactWithCompleteFields()
    {
        return [
            "Email" => $this->request->getEmail(),
            "FirstName" => $this->request->getFirstName(),
            "LastName" => $this->request->getLastName(),
            "Phone1" => $this->request->getPhoneNumber(),
            "State" => $this->request->getBillState(),
            "Country" => $this->request->getBillCountry(),
            "City" => $this->request->getBillCity(),
            "StreetAddress1" => $this->request->getBillAddress1(),
            "StreetAddress2" => $this->request->getBillAddress2(),
            "PostalCode" => $this->request->getBillZip(),
            "DateCreated" => new \DateTime('now')
        ];
    }

    private function getContactWithNameOnly()
    {
        return [
            "Email" => $this->request->getEmail(),
            "FirstName" => '',
            "LastName" => '',
            "Phone1" => '',
            "State" => '',
            "Country" => '',
            "City" => '',
            "StreetAddress1" => '',
            "StreetAddress2" => '',
            "PostalCode" => '',
            "DateCreated" => new \DateTime('now')
        ];
    }

}


