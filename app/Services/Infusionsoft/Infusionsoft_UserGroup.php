<?php
namespace App\Services\Infusionsoft;

use App\Services\Infusionsoft\Generated\Infusionsoft_Generated_UserGroup;

class Infusionsoft_UserGroup extends Infusionsoft_Generated_UserGroup{	
    public function __construct($id = null, $app = null){
    	parent::__construct($id, $app);    	    	
    }
}

