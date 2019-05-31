<?php

namespace App\Services\InfusionsoftV2\OAuth2\API;

use Infusionsoft\Api\OrderService as APIOrderService;
use Infusionsoft\Infusionsoft;


Class OrderService extends APIOrderService
{  
    protected $table = 'Job';
    
   protected static $tableFields = array('Id', 'JobTitle', 'ContactId', 'StartDate', 'DueDate', 'JobNotes', 'ProductId', 'JobRecurringId', 'JobStatus', 'DateCreated', 'OrderType', 'OrderStatus', 'ShipFirstName', 'ShipMiddleName', 'ShipLastName', 'ShipCompany', 'ShipPhone', 'ShipStreet1', 'ShipStreet2', 'ShipCity', 'ShipState', 'ShipZip', 'ShipCountry', 'LastUpdated');

    public function __construct(Infusionsoft $client)
    {
        parent::__construct($client);
    }

    public function getFields()
    {
        return self::$tableFields;
    }
}
