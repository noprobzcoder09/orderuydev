<?php

namespace App\Services\InfusionsoftV2\OAuth2\API;

use Infusionsoft\Api\DataService as APIDataService;
use Infusionsoft\Infusionsoft;


Class ProductService extends APIDataService
{  

    protected static $tableFields = array('Id', 'ProductName', 'ProductPrice', 'Sku', 'ShortDescription', 'Taxable', 'Weight', 'IsPackage', 'NeedsDigitalDelivery', 'Description', 'HideInStore', 'Status', 'TopHTML', 'BottomHTML', 'ShippingTime', 'InventoryNotifiee', 'InventoryLimit', 'Shippable');

    public function __construct(Infusionsoft $client)
    {
        parent::__construct($client);
    }

    public function getFields()
    {
        return self::$tableFields;
    }
}
