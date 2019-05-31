<?php

namespace App\Services\InfusionsoftV2\OAuth2\API;

use App\Services\InfusionsoftV2\OAuth2\API\ContactService;
use App\Services\InfusionsoftV2\OAuth2\API\CreditCardService;
use App\Services\InfusionsoftV2\OAuth2\API\InvoiceService;
use App\Services\InfusionsoftV2\OAuth2\API\DataService;
use App\Services\InfusionsoftV2\OAuth2\API\DataFormField;
use App\Services\InfusionsoftV2\OAuth2\API\ProductService;

use Infusionsoft\Infusionsoft;

Trait TableServiceProvider
{   
   
   public function getTableServiceProvider(Infusionsoft $client, string $table)
   {
        switch(strtolower($table))
        {
            case 'invoice':
            $object = new InvoiceService($client);
                break;
            case 'creditcard':
            $object = new CreditCardService($client);
                break;
            case 'contact':
            $object = new ContactService($client);
                break;
            case 'dataformfield':
            $object = new DataFormField($client);
                break;
            case 'product':
            $object = new ProductService($client);
                break;
            default:
                throw new \Exception(__("Could not found table service."), 1);
                
        }
        
        return $object;
   }
    
}
