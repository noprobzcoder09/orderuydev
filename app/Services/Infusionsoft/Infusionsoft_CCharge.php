<?php

namespace App\Services\Infusionsoft;

use App\Services\Infusionsoft\Generated\Infusionsoft_Generated_CCharge;

class Infusionsoft_CCharge extends Infusionsoft_Generated_CCharge{	
    public function __construct($id = null, $app = null){
    	parent::__construct($id, $app);    	    	
    }
}

