<?php
namespace App\Services\Infusionsoft;

use App\Services\Infusionsoft\Generated\Infusionsoft_Generated_ProductOptionValue;

class Infusionsoft_ProductOptionValue extends Infusionsoft_Generated_ProductOptionValue {
    public function __construct($id = null, $app = null){
    	parent::__construct($id, $app);    	    	
    }
}