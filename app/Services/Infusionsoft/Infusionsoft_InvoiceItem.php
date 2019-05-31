<?php
namespace App\Services\Infusionsoft;

use App\Services\Infusionsoft\Generated\Infusionsoft_Generated_InvoiceItem;

class Infusionsoft_InvoiceItem extends Infusionsoft_Generated_InvoiceItem{	
    public function __construct($id = null, $app = null){
    	parent::__construct($id, $app);    	    	
    }
}

