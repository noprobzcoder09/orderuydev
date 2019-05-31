<?php
namespace App\Services\Infusionsoft;

use App\Services\Infusionsoft\Generated\Infusionsoft_Generated_Status;

class Infusionsoft_Status extends Infusionsoft_Generated_Status{	
    public function __construct($id = null, $app = null){
    	parent::__construct($id, $app);    	    	
    }
}

