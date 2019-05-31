<?php
namespace App\Services\Infusionsoft;

use App\Services\Infusionsoft\Generated\Infusionsoft_Generated_ProductInterest;

class Infusionsoft_ProductInterest extends Infusionsoft_Generated_ProductInterest{	
    public function __construct($id = null, $app = null){
    	parent::__construct($id, $app);    	    	
    }
}

