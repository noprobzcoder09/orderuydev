<?php
namespace App\Services\Infusionsoft;

use App\Services\Infusionsoft\Generated\Infusionsoft_Generated_TicketType;

class Infusionsoft_TicketType extends Infusionsoft_Generated_TicketType{	
    public function __construct($id = null, $app = null){
    	parent::__construct($id, $app);    	    	
    }
}

