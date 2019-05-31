<?php

namespace App\Services\Infusionsoft;

use App\Services\Infusionsoft\Generated\Infusionsoft_Generated_ContactGroup;

class Infusionsoft_ContactGroup extends Infusionsoft_Generated_ContactGroup{	
    public function __construct($id = null, $app = null){
    	parent::__construct($id, $app);    	    	
    }
}

