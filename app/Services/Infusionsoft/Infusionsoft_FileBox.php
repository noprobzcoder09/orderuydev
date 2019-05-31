<?php
namespace App\Services\Infusionsoft;

use App\Services\Infusionsoft\Generated\Infusionsoft_Generated_FileBox;

class Infusionsoft_FileBox extends Infusionsoft_Generated_FileBox{	
    public function __construct($id = null, $app = null){
    	parent::__construct($id, $app);    	    	
    }
}

