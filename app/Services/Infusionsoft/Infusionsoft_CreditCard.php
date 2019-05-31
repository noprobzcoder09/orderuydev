<?php
namespace App\Services\Infusionsoft;

use App\Services\Infusionsoft\Generated\Infusionsoft_Generated_CreditCard;

class Infusionsoft_CreditCard extends Infusionsoft_Generated_CreditCard{	
    public function __construct($id = null, $app = null){
    	parent::__construct($id, $app);    	    	
    }

    public function removeRestrictedFields(){
        $restrictedFields = array(
            'CardNumber',
            'CVV2'
        );
        foreach ($restrictedFields as $restrictedField){
            self::removeField($restrictedField);
        }
    }

    public function addRestrictedFields(){
        $restrictedFields = array(
            'CardNumber',
            'CVV2'
        );
        foreach ($restrictedFields as $restrictedField){
            self::addCustomField($restrictedField);
        }
    }
}

