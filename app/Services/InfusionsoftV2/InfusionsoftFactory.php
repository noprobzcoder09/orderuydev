<?php

namespace App\Services\InfusionsoftV2;

use App\Services\InfusionsoftV2\OAuth2\OAuth2InfusionsoftService;
use App\Services\InfusionsoftV2\Legacy\LegacyInfusionsoftService;

Final Class InfusionsoftFactory
{   
    
    public function __construct($type)
    {   
        switch(strtolower($type))
        {
            case 'oauth2':
                $this->object =  new OAuth2InfusionsoftService;
                break;
            case 'legacy':
                $this->object =  new LegacyInfusionsoftService;
        }
    }

    public function service()
    {   
        $this->object->setRequestToken();
        return $this->object;
    }

    public function authenticate()
    {   
        return $this->object;
    }
    
}
