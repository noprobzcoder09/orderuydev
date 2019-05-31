<?php

namespace App\Services\Customers\Checkout\Extended;

use Request as R;

use \App\Services\Manageplan\Contracts\Request as RequestContract;

use \App\Services\Manageplan\Request as RequestParent;
use App\Services\Customers\Checkout\SessionRegistration;

Class Request extends RequestParent implements RequestContract
{   
        
    public function getLunch()
    {
        return json_decode(R::get('lunch'));
    }

    public function getDinner()
    {
        return json_decode(R::get('dinner'));
    }

    public function getMeals()
    {
        return array_merge($this->getLunch(), $this->getDinner());
    }

    public function getCustomerName()
    {
        return R::get('first_name').' '.R::get('last_name');
    }

    public function getUser()
    {
        $sessionRegister = new SessionRegistration;
        $data =  $sessionRegister->get();

        $data['name'] = $this->getCustomerName();
        $data['dietary_notes'] = '';
        
        $data = array_merge($data, R::all());

        return $data;
    }

    public function getCardName() {
        return R::get('card_name');
    }

    public function getCardNumber() {
        return str_replace(' ','',R::get('card_number'));
    }

    public function getExpMonth() {
        $month = explode('/',R::get('card_expiration_date'))[0];
        return $month;
    }

    public function getExpYear() {
        $year = explode('/',R::get('card_expiration_date'))[1];
        return $year;
    }

    public function getCardCVC() {
        return R::get('card_cvc');
    }

    public function getCard() {
        return strtolower(R::get('card'));
    }

    public function getCardLast4()
    {
        return substr($this->getCard(),0, -4);
    }

    public function isCardNew() {
        return $this->getCard() == '' || $this->getCard() == 'undefined' || $this->getCard() == 'new';
    }

    public function getFirstName()
    {
        return R::get('first_name');
    }

    public function getLastName()
    {
        return R::get('last_name');
    }

    public function getBillName()
    {
        return $this->getCustomerName();
    }

    public function getBillAddress1()
    {
        return R::get('address1');
    }

    public function getBillAddress2()
    {
        return R::get('address2');
    }

    public function getBillCity()
    {
        return R::get('suburb');
    }

    public function getBillState()
    {
        return R::get('country');
    }

    public function getBillZip()
    {
        return R::get('postcode');
    }

    public function getBillCountry()
    {
        return R::get('country');
    }

    public function getPhoneNumber()
    {
        return R::get('mobile_phone');
    }

    public function getEmail()
    {   
        return R::get('email');
    }

    public function auth()
    {   
        return R::get('auth');
    }

    public function getDeliveryNotes()
    {   
        return R::get('delivery_notes');
    } 

    public function getPassword()
    {   
        return R::get('password');
    }    

    public function getConfirmPassword()
    {   
        return R::get('confirm_password');
    }    

    public function getPromoCode()
    {   
        return is_null(R::get('coupon_code')) ? '' : R::get('coupon_code');
    }    
    
}
