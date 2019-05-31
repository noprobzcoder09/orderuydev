<?php

namespace App\Services\InfusionsoftV2;

Final Class CustomField
{   
    
   private static $fields = array(
        "_ActiveWeekMenu" => 9, 
        "_ActiveLocation" => 13, 
        "_NextDeliveryLocation" => 3, 
        "_DeliveryMenu" => 5, 
        "_PausedCancelledPlans" => 11, 
        "_ActiveWeekCutOffDate" => 15, 
        "_ActiveWeekCutOff" => 7, 
        "_NextDeliveryDate" => 1, 
        "_DeliveryDatePretty" => 23,
        "_PausedTillDate" => 17,
        '_ActiveWeekDeliveryDate' => 19,
        '_ActiveWeekDeliveryDatePretty' => 21,
        '_ActiveDeliveryAddress' => 31,
        '_DeliveryAddress' => 33
    );

    public function getActiveWeekMenu() {
        return array_keys(self::$fields)[0];
    }

    public function getActiveWeekMenuId() {
        return self::$fields[$this->getActiveWeekMenu()];
    }

    public function getActiveWeekDeliveryDate() {
        return array_keys(self::$fields)[10];
    }

    public function getActiveWeekDeliveryDateId() {
        return self::$fields[$this->getActiveWeekDeliveryDate()];
    }

    public function getActiveWeekDeliveryDatePretty() {
        return array_keys(self::$fields)[11];
    }

    public function getActiveWeekDeliveryDatePrettyId() {
        return self::$fields[$this->getActiveWeekDeliveryDatePretty()];
    }

    public function getActiveLocation() {
        return array_keys(self::$fields)[1];
    }

    public function getActiveLocationId() {
        return self::$fields[$this->getActiveLocation()];
    }

    public function getNextDeliveryLocation() {
        return array_keys(self::$fields)[2];
    }

    public function getNextDeliveryLocationId() {
        return self::$fields[$this->getNextDeliveryLocation()];
    }

    public function getDeliveryMenu() {
        return array_keys(self::$fields)[3];
    }

    public function getDeliveryMenuId() {
        return self::$fields[$this->getDeliveryMenu()];
    }

    public function getPausedCancelledPlans() {
        return array_keys(self::$fields)[4];
    }

    public function getPausedCancelledPlansId() {
        return self::$fields[$this->getPausedCancelledPlans()];
    }

    public function getActiveWeekCutOffDate() {
        return array_keys(self::$fields)[5];
    }

    public function getActiveWeekCutOffDateId() {
        return self::$fields[$this->getActiveWeekCutOffDate()];
    }

    public function getActiveWeekCutOff() {
        return array_keys(self::$fields)[6];
    }

    public function getActiveWeekCutOffId() {
        return self::$fields[$this->getActiveWeekCutOff()];
    }

    public function getNextDeliveryDate() {
        return array_keys(self::$fields)[7];
    }

    public function getNextDeliveryDateId() {
        return self::$fields[$this->getNextDeliveryDate()];
    }

    public function getPausedTillDate() {
        return array_keys(self::$fields)[9];
    }

    public function getPausedTillDateId() {
        return self::$fields[$this->getPausedTillDate()];
    }

    public function getDeliveryDatePretty() {
        return array_keys(self::$fields)[8];
    }

    public function getDeliveryDatePrettyId() {
        return self::$fields[$this->getDeliveryDatePretty()];
    }

    public function getActiveDeliveryAddress() {
        return array_keys(self::$fields)[12];
    }

    public function getActiveDeliveryAddressId() {
        return self::$fields[$this->getActiveDeliveryAddress()];
    }

    public function getDeliveryAddress() {
        return array_keys(self::$fields)[13];
    }

    public function getDeliveryAddressId() {
        return self::$fields[$this->getDeliveryAddress()];
    }
    
}
