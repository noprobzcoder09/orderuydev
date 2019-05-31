<?php

namespace App\Services\Customers\Account;

Class BillingProfile
{  
    /**
    * This class is being used in other services
    * Modifying this class may cause conflicts in the system
    *
    */

    public function getFirstName()
    {
        return $this->user->row->first_name;
    }

    public function getLastName()
    {
        return $this->user->row->last_name;
    }

    public function getBillName()
    {
        return $this->user->row->billing_first_name.' '.$this->user->row->billing_last_name;
    }

    public function getBillAddress1()
    {
        return $this->user->rowAddress->address1;
    }

    public function getBillAddress2()
    {
        return $this->user->rowAddress->address2;
    }

    public function getBillCity()
    {
        return $this->user->rowAddress->suburb;
    }

    public function getBillState()
    {
        return $this->user->rowAddress->state;
    }

    public function getBillZip()
    {
        return $this->user->rowAddress->postcode;
    }

    public function getBillCountry()
    {
        return $this->user->rowAddress->country;
    }

    public function getPhoneNumber()
    {
        return $this->user->row->mobile_phone;
    }

    public function getEmail()
    {   
        return $this->user->model->email($this->user->row->user_id);
    }
}
