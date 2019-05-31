<?php
namespace App\Services\Infusiosoft;

use App\Services\Infusionsoft\Generated\Infusionsoft_Generated_ProductCategory;

class Infusionsoft_ProductCategory extends Infusionsoft_Generated_ProductCategory{
    public function __construct($id = null, $app = null){
    	parent::__construct($id, $app);    	    	
    }
}

