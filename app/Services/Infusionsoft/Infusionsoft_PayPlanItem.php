<?php
namespace App\Services\Infusionsoft;

use App\Services\Infusionsoft\Generated\Infusionsoft_Generated_PayPlanItem;

class Infusionsoft_PayPlanItem extends Infusionsoft_Generated_PayPlanItem{	
    public function __construct($id = null, $app = null){
    	parent::__construct($id, $app);    	    	
    }
}

