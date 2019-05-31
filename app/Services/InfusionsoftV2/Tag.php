<?php

namespace App\Services\InfusionsoftV2;

Final Class Tag
{   
    public $tags_ar = array(
        "New Customer" => 133, 
        "Failed Billing" => 119,
        "Cancelled A Plan" => 117,
        "Paused A Plan" => 115,
        "New Week" => 113,
        "Menu Selections Saved" => 111,
        "Cancelled" => 109,
        "Billing Issue" => 107,
        "Paused" => 105,
        "Active" => 103,
        "End of Week" => 131,
        "Active Menu For Delivery" => 129,
        'Cancelled Delivery' => 151
   );

    public function get($key=''){
        $key = ucfirst($key);
        if($key == "" || !array_key_exists($key, $this->tags_ar)) return 0;

        return $this->tags_ar[$key];
    } 

    public function getNewCustomer()
    {
        return array_keys($this->tags_ar)[0];
    }

    public function getFailedBilling()
    {
        return array_keys($this->tags_ar);
    }

    public function getCancelledPlan()
    {
        return array_keys($this->tags_ar)[2];
    }

    public function getCancelledPlanId()
    {
        return $this->get($this->getCancelledPlan());
    }

    public function getPlanPaused()
    {
        return array_keys($this->tags_ar)[3];
    }

    public function getPlanPausedId()
    {
        return $this->get($this->getPlanPaused());
    }

    public function getNewWeek()
    {
        return array_keys($this->tags_ar)[4];
    }

    public function getMenuSaved()
    {
        return array_keys($this->tags_ar)[5];
    }

    public function getMenuSavedId()
    {
        return $this->get(array_keys($this->tags_ar)[5]);
    }

    public function getCancelled()
    {
        return array_keys($this->tags_ar)[6];
    }

    public function getBillingIssue()
    {
        return array_keys($this->tags_ar)[7];
    }

    public function getPaused()
    {
        return array_keys($this->tags_ar)[8];
    }

    public function getActive()
    {
        return array_keys($this->tags_ar)[9];
    }

    public function getEndWeek()
    {
        return array_keys($this->tags_ar)[10];
    }

    public function getActiveMenuDelivery()
    {
        return array_keys($this->tags_ar)[11];
    }

    public function getActiveMenuDeliveryId()
    {
        return $this->get(array_keys($this->tags_ar)[11]);
    }

    public function getCancelledWeek()
    {
        return array_keys($this->tags_ar)[12];
    }

    public function getCancelledWeekId()
    {
        return $this->get($this->getCancelledWeek());
    }

}
