<?php

namespace App\Services\Infusionsoft;

use App\Services\Infusionsoft\Generated\Infusionsoft_TokenStorageProvider;

class Infusionsoft_Campaignee extends Infusionsoft_Generated_Campaignee{	
    public function __construct($id = null, $app = null){
    	parent::__construct($id, $app);    	    	
    }
}

