<?php
namespace App\Services\Infusiosoft;

use App\Services\Infusionsoft\Generated\Infusionsoft_Generated_ProductCategoryAssign;

class Infusionsoft_ProductCategoryAssign extends Infusionsoft_Generated_ProductCategoryAssign{
    public function __construct($id = null, $app = null){
    	parent::__construct($id, $app);    	    	
    }
}

