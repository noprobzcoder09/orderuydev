<?php

namespace App\Services\Card;

use App\Services\Card\Contracts\Gateway as GatewayInterface;
use App\Services\InfusionSoftServices;

Class Contact extends InfusionSoftServices
{   
    public $fields = array(
        "Email",
        "FirstName",
        "LastName",
        // "Phone1",
        "State",
        "Country",
        "City",
        "StreetAddress1",
        // "StreetAddress2",
        "PostalCode",
        "DateCreated"
    );

    protected $api;

    public function __construct($api = null)
    {
        if (is_null($api)) {
            parent::__construct();
        } else {
            $this->api = $api;
        }
    }

    public function create(array $data)
    {   
        $id = $this->deDupeContact(null, $data);

        if (empty($id)) {
            throw new \Exception(sprintf(__('crud.failedToCreate'),'contact'), 1);
            
        }

        $this->setId($id);
    }

    public function update(int $contactId, array $data)
    {   
        $id = $this->deDupeContact($contactId, $data);

        if (empty($id)) {
            throw new \Exception(sprintf(__('crud.failedToUpdate'),'contact'), 1);
            
        }

        $this->setId($id);
    }

    public function optInEmail(string $email, string $optInReason = 'Opted In as per request')
    {   
        $this->optIn($email, $optInReason);
    }

    public function setId(int $id) 
    {
        $this->id = $id;
    }

    public function getId() 
    {
        return $this->id;
    }
}
