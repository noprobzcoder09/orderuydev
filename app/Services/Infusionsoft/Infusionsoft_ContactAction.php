<?php
namespace App\Services\Infusionsoft;

use App\Services\Infusionsoft\Generated\Infusionsoft_Generated_ContactAction;

class Infusionsoft_ContactAction extends Infusionsoft_Generated_ContactAction{
    var $customFieldFormId = -5;
    public function __construct($id = null, $app = null){
    	parent::__construct($id, $app);    	    	
    }
}

