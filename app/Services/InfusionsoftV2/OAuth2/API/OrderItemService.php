<?php

namespace App\Services\InfusionsoftV2\OAuth2\API;

use Infusionsoft\Api\ContactService as APIContactService;
use Infusionsoft\Api\DataService;
use Infusionsoft\Infusionsoft;


Class OrderItemService extends APIContactService
{  
    protected $table = 'OrderItem';
    
    protected static $tableFields = array(
        'Id', 
        'OrderId', 
        'ProductId', 
        'SubscriptionPlanId', 
        'ItemName', 
        'Qty', 
        'CPU', 
        'PPU', 
        'ItemDescription', 
        'ItemType', 
        'Notes'
    );

    public function __construct(Infusionsoft $client)
    {
        parent::__construct($client);
        $this->dataService = new DataService($client);
    }

    public function getFields()
    {
        return self::$tableFields;
    }

    public function getOrderItemsByOrderId(int $orderId): array
    {   
        return
        $this->dataService->query(
            $this->table, 
            1000, 
            0, 
            array('OrderId' => $orderId), 
            self::$tableFields, 
            'Id', 
            true
        );
    }
}
