<?php

namespace App\Services\InfusionsoftV2\OAuth2\API;

use Infusionsoft\Api\InvoiceService as APIInvoiceService;
use Infusionsoft\Infusionsoft;


Class DataFormField extends APIInvoiceService
{  
    protected static $tableFields = array(
        'DataType', 
        'Id', 
        'FormId', 
        'GroupId', 
        'Name', 
        'Label', 
        'DefaultValue', 
        'Values', 
        'ListRows'
    );

    public function __construct(Infusionsoft $client)
    {   
        $this->client = $client;
        parent::__construct($client);
    }

    public function getFields()
    {
        return self::$tableFields;
    }

}
