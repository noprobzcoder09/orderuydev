<?php

namespace App\Services\Infusionsoft;

use App\Services\Infusionsoft\Infusionsoft_Service;

class Infusionsoft_DataServiceBase extends Infusionsoft_Service{

    public static function authenticateUser($username, $passwordHash, Infusionsoft_App $app = null){
        $params = array(
            $username,
            $passwordHash
        );
        return parent::send($app, "DataService.authenticateUser", $params);
    }

}