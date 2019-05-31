<?php

namespace App\Services\InfusionsoftV2\OAuth2\API;

use Infusionsoft\Api\ContactService as APIContactService;
use Infusionsoft\Infusionsoft;


Class ContactService extends APIContactService
{  
    protected $table = 'Contact';
    
    protected static $tableFields = array('Address1Type', 'Address2Street1', 'Address2Street2', 'Address2Type', 'Address3Street1', 'Address3Street2', 'Address3Type', 'Anniversary', 'AssistantName', 'AssistantPhone', 'BillingInformation', 'Birthday', 'City', 'City2', 'City3', 'Company', 'AccountId', 'CompanyID', 'ContactNotes', 'ContactType', 'Country', 'Country2', 'Country3', 'CreatedBy', 'DateCreated', 'Email', 'EmailAddress2', 'EmailAddress3', 'Fax1', 'Fax1Type', 'Fax2', 'Fax2Type', 'FirstName', 'Groups', 'Id', 'JobTitle', 'Language', 'LastName', 'LastUpdated', 'LastUpdatedBy', 'Leadsource', 'LeadSourceId', 'MiddleName', 'Nickname', 'OwnerID', 'Password', 'Phone1', 'Phone1Ext', 'Phone1Type', 'Phone2', 'Phone2Ext', 'Phone2Type', 'Phone3', 'Phone3Ext', 'Phone3Type', 'Phone4', 'Phone4Ext', 'Phone4Type', 'Phone5', 'Phone5Ext', 'Phone5Type', 'PostalCode', 'PostalCode2', 'PostalCode3', 'ReferralCode', 'SpouseName', 'State', 'State2', 'State3', 'StreetAddress1', 'StreetAddress2', 'Suffix', 'TimeZone', 'Title', 'Username', 'Validated', 'Website', 'ZipFour1', 'ZipFour2', 'ZipFour3');

    public function __construct(Infusionsoft $client)
    {
        parent::__construct($client);
    }

    public function getFields()
    {
        return self::$tableFields;
    }

    public function getContactByEmail($email, $selectedFields = array())
    {
        if (empty($selectedFields)) {
            $selectedFields = $this->getFields();
        }

        return $this->findByEmail($email, $selectedFields);
    }

    public function add($data): int
    {   
        return $this->client->request('ContactService.add', $data);
    }

    public function update($contactId, $data): int
    {   
        return $this->client->request(
            'ContactService.update', $contactId, $data
        );
    }

    public function loadFromArray($data, $fast = false)
    {   
        $arrayData = array();
        if($fast){
            $arrayData = $data;
        } else {
            foreach ($this->getFields() as $field){
                $arrayData[$field] = '';
                if ($data && is_array($data) && isset($data[$field])){
                    $arrayData[$field] = $data[$field];
                }
            }
        }

        return $arrayData;
    }

    public function removeField($fieldName){
        $fieldIndex = array_search($fieldName, self::$tableFields);
        if($fieldIndex !== false){
            unset(self::$tableFields[$fieldIndex]);
            self::$tableFields = array_values(self::$tableFields);
        }
    }

    public function removeReadOnlyFields(){
        $readOnlyFields = array(
            'CreatedBy',
            'DateCreated',
            'Groups',
            'Id',
            'LastUpdated',
            'LastUpdatedBy',
            'Validated',
        );
        foreach ($readOnlyFields as $field){
            $this->removeField($field);
        }

    }

}
