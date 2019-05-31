<?php
namespace App\Services\Infusionsoft;

use App\Services\Infusionsoft\Generated\Infusionsoft_Generated_Ticket;

class Infusionsoft_Ticket extends Infusionsoft_Generated_Ticket{	
    public function __construct($id = null, $app = null){
    	parent::__construct($id, $app);    	    	
    }
}

