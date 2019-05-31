<?php

namespace App\Services\InfusionsoftV2\OAuth2\API;

use Infusionsoft\Api\DataService as APIDataService;
use Infusionsoft\Infusionsoft;


Class DataService extends APIDataService
{  
    public function __construct(Infusionsoft $client)
    {
        parent::__construct($client);
    }
}
