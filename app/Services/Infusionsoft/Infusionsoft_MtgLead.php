<?php
namespace App\Services\Infusionsoft;

use App\Services\Infusionsoft\Generated\Infusionsoft_Generated_MtgLead;

class Infusionsoft_MtgLead extends Infusionsoft_Generated_MtgLead{	
    public function __construct($id = null, $app = null){
    	parent::__construct($id, $app);    	    	
    }
}

