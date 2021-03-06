<?php
/**
 * @property String Id
 * @property String CategoryId
 * @property String Label
 */
namespace App\Services\Infusionsoft\Generated;

use App\Services\Infusionsoft\Generated\Infusionsoft_Generated_Base;

class Infusionsoft_Generated_TicketType extends Infusionsoft_Generated_Base{
    protected static $tableFields = array('Id', 'CategoryId', 'Label');
    
    
    public function __construct($id = null, $app = null){    	    	
    	parent::__construct('TicketType', $id, $app);    	    	
    }
    
    public function getFields(){
		return self::$tableFields;	
	}
	
	public function addCustomField($name){
		self::$tableFields[] = $name;
	}

    public function removeField($fieldName){
        $fieldIndex = array_search($fieldName, self::$tableFields);
        if($fieldIndex !== false){
            unset(self::$tableFields[$fieldIndex]);
            self::$tableFields = array_values(self::$tableFields);
        }
    }
}
