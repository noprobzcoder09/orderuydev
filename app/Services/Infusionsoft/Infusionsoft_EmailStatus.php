<?php
/**
 * Created by PhpStorm.
 * User: Erik
 * Date: 2/9/2015
 * Time: 11:58 AM
 */
namespace App\Services\Infusionsoft;

use App\Services\Infusionsoft\Generated\Infusionsoft_Generated_Base;

class Infusionsoft_EmailStatus  extends Infusionsoft_Generated_Base {
    protected static $tableFields = array(
        "Id",
        "ContactId",
        "Name",
        "DateCreated",
        "Status",
        "OptType"
    );

    public function __construct($idString = null, $app = null){
        $this->table = 'EmailStatus';
    }

    public function getFields(){
        return self::$tableFields;
    }

    public function save($app = null) {
        throw new Infusionsoft_Exception("EmailStatus cannot be saved since they are loaded from a saved search, and not accessible via the Data Service");
    }

    public function loadFromArray($data, $fast = false){
        parent::loadFromArray($data, true);
        $this->Id = $data['Id'];
    }
}