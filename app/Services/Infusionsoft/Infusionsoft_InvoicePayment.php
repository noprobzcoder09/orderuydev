<?php
namespace App\Services\Infusionsoft;

use App\Services\Infusionsoft\Generated\Infusionsoft_Generated_InvoicePayment;

class Infusionsoft_InvoicePayment extends Infusionsoft_Generated_InvoicePayment{	
    public function __construct($id = null, $app = null){
    	parent::__construct($id, $app);    	    	
    }
}

