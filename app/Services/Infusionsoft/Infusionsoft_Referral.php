<?php
namespace App\Services\Infusionsoft;

use App\Services\Infusionsoft\Generated\Infusionsoft_Generated_Referral;

class Infusionsoft_Referral extends Infusionsoft_Generated_Referral{	
    public function __construct($id = null, $app = null){
    	parent::__construct($id, $app);    	    	
    }
}

