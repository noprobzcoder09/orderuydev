<?php
namespace App\Services\Infusionsoft;

use App\Services\Infusiosoft\Generated\Infusionsoft_Generated_JobRecurringInstance;

class Infusionsoft_JobRecurringInstance extends Infusionsoft_Generated_JobRecurringInstance{	
    public function __construct($id = null, $app = null){
    	parent::__construct($id, $app);    	    	
    }
}

