<?php
namespace App\Services\Infusionsoft;

use App\Services\Infusionsoft\Generated\Infusionsoft_Generated_ProductOption;

class Infusionsoft_ProductOption extends Infusionsoft_Generated_ProductOption {
    public function __construct($id = null, $app = null){
    	parent::__construct($id, $app);    	    	
    }
}

