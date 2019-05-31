<?php

namespace App\Services\Card\Contracts;

Interface Card
{   
    public function setFirstName(string $name);
    public function getFirstName();
    public function setLastName(string $name);
    public function getLastName();
    public function setName(string $name);
    public function getName();
    public function setCardNumber(string $number);
    public function getCardNumber();
    public function getCardNumberLast4();
    public function setExpMonth(string $month);
    public function getExpMonth();
    public function setExpYear(string $year);
    public function getExpYear();
    public function setCVC(string $cvc);
    public function getCVC();
    public function setType(string $cardNumber);
    public function getType();
    public function set(array $data);
    public function get();
    public function setBillName(string $value);
    public function getBillName();
    public function setBillAddress1(string $value);
    public function getBillAddress1();
    public function setBillAddress2($value = '');
    public function getBillAddress2();
    public function setBillCity(string $value);
    public function getBillCity();
    public function setBillState(string $value);
    public function getBillState();
    public function setBillZip(string $value);
    public function getBillZip();
    public function setBillCountry(string $value);
    public function getBillCountry();
    public function setPhoneNumber(string $value);
    public function getPhoneNumber();
    public function setEmail(string $value);
}
