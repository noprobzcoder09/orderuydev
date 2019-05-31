<?php
namespace App\Services\Infusionsoft;

use App\Services\Infusionsoft\Generated\Infusionsoft_Generated_RecurringOrderWithContact;

class Infusionsoft_RecurringOrderWithContact extends Infusionsoft_Generated_RecurringOrderWithContact{	
    public function __construct($id = null, $app = null){
    	parent::__construct($id, $app);    	    	
    }
}

