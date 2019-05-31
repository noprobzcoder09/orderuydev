<?php
namespace App\Services\Infusionsoft;

use App\Services\Infusionsoft\Generated\Infusionsoft_Generated_Product;

class Infusionsoft_Product extends Infusionsoft_Generated_Product{	
    public function __construct($id = null, $app = null){
    	parent::__construct($id, $app);    	    	
    }
}

