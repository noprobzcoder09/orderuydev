<?php

namespace App\Services\Infusionsoft;

use App\Services\Infusionsoft\Generated\Infusionsoft_Generated_Company;

class Infusionsoft_Company extends Infusionsoft_Generated_Company{
    var $customFieldFormId = -6;
    public function __construct($id = null, $app = null){
    	parent::__construct($id, $app);    	    	
    }
}

