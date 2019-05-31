<?php
namespace App\Services\Infusionsoft;

use App\Services\Infusionsoft\Generated\Infusionsoft_Generated_LeadSource;

class Infusionsoft_LeadSource extends Infusionsoft_Generated_LeadSource{	
    public function __construct($id = null, $app = null){
    	parent::__construct($id, $app);    	    	
    }
}

