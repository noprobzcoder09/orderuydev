<?php

namespace App\Services\Infusionsoft;

use App\Services\Infusionsoft\Generated\Infusionsoft_Generated_DataFormGroup;

class Infusionsoft_DataFormGroup extends Infusionsoft_Generated_DataFormGroup{	
    public function __construct($id = null, $app = null){
    	parent::__construct($id, $app);    	    	
    }
}

