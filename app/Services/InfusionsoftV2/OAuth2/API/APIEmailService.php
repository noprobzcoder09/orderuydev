<?php

namespace App\Services\InfusionsoftV2\OAuth2\API;

use Infusionsoft\Api\APIEmailService as APIAPIEmailService;
use Infusionsoft\Infusionsoft;


Class APIEmailService extends APIAPIEmailService
{  
    public function __construct(Infusionsoft $client)
    {   
        $this->client = $client;
        parent::__construct($client);
    }

}
