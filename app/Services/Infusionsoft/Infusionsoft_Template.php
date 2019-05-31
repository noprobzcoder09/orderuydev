<?php
namespace App\Services\Infusionsoft;

use App\Services\Infusionsoft\Generated\Infusionsoft_Generated_Template;

class Infusionsoft_Template extends Infusionsoft_Generated_Template{	
    public function __construct($id = null, $app = null){
    	parent::__construct($id, $app);    	    	
    }
}

