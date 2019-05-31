<?php

namespace App\Services\InfusionsoftV2\OAuth2\API;

use Infusionsoft\Api\InvoiceService as APIInvoiceService;
use Infusionsoft\Infusionsoft;


Class InvoiceService extends APIInvoiceService
{  
    protected static $tableFields = array(
        'Id',
        'ContactId', 
        'JobId', 
        'DateCreated', 
        'InvoiceTotal', 
        'TotalPaid', 
        'TotalDue', 
        'PayStatus', 
        'CreditStatus', 
        'RefundStatus', 
        'PayPlanStatus', 
        'AffiliateId', 
        'LeadAffiliateId', 
        'PromoCode', 
        'InvoiceType', 
        'Description', 
        'ProductSold', 
        'Synced', 
        'LastUpdated'
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
    
    // public function createBlankOrder($contactId, $description, $orderDate, $leadAffiliateId, $saleAffiliateId)
    // {
    //     return $this->client->invoices('xml')->createBlankOrder($contactId, $description, $orderDate, $leadAffiliateId, $saleAffiliateId);
    // }
}
