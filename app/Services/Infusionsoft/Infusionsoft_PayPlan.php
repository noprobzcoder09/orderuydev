<?php
namespace App\Services\Infusionsoft;

use App\Services\Infusionsoft\Generated\Infusionsoft_Generated_PayPlan;

class Infusionsoft_PayPlan extends Infusionsoft_Generated_PayPlan{	
    public function __construct($id = null, $app = null){
    	parent::__construct($id, $app);    	    	
    }
}

