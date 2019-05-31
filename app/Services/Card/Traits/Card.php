<?php

namespace App\Services\Card\Traits;

use Request;

Trait Card
{  
    /**
    * This trait is being used in other services
    * Modifying this trait may cause conflicts in the system
    *
    */

    public function getCardName() {
        return Request::get('card_name');
    }

    public function getCardNumber() {
        return str_replace(' ','',Request::get('card_number'));
    }

    public function getExpMonth() {
        $month = explode('/',Request::get('card_expiration_date'))[0];
        return $month;
    }

    public function getExpYear() {
        $year = explode('/',Request::get('card_expiration_date'))[1];
        return $year;
    }

    public function getCardCVC() {
        return Request::get('card_cvc');
    }

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
        $out = $this->user->row->mobile_phone;
        return is_null($out) ? '' : $out;
    }

    public function getEmail()
    {   
        return $this->user->model->email($this->user->row->user_id);
    }
}
