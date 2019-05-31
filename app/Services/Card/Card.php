<?php

namespace App\Services\Card;

use App\Services\Card\Contracts\Card as CardInterface;

use App\Services\Card\Type;

Class Card extends Type implements CardInterface
{   
    private $cardNumber;
    private $expMonth;
    private $expYear;
    private $cvc;
    private $type;
    private $name;
    private $firstName;
    private $lastName;
    private $billName;
    private $billAddress1;
    private $billAddress2;
    private $billCity;
    private $billState;
    private $billZip;
    private $billCountry;
    private $phoneNumber;
    private $email;
    private $data;

    public function setFirstName(string $name)
    {
        $this->firstName = $name;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setLastName(string $name)
    {
        $this->lastName = $name;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setCardNumber(string $number)
    {
        $this->cardNumber = $number;
    }

    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    public function getCardNumberLast4()
    {
        return substr($this->getCardNumber(),-4);
    }

    public function setExpMonth(string $month)
    {
        $this->expMonth = $month;
    }

    public function getExpMonth()
    {
        return $this->expMonth;
    }

    public function setExpYear(string $year)
    {
        $this->expYear = '20'.$year;
    }

    public function getExpYear()
    {
        return $this->expYear;
    }

    public function setCVC(string $cvc)
    {
        $this->cvc = $cvc;
    }

    public function getCVC()
    {
        return $this->cvc;
    }

    public function setType(string $cardNumber)
    {
        $this->type = $this->getCardType($cardNumber);
        
    }

    public function getType()
    {
        return $this->type;
    }

    public function setBillName(string $value)
    {
        $this->billName = $value;
    }

    public function getBillName()
    {
        return $this->billName;
    }

    public function setBillAddress1(string $value)
    {
        $this->billAddress1 = $value;
    }

    public function getBillAddress1()
    {
        return $this->billAddress1;
    }

    public function setBillAddress2($value = '')
    {
        $this->billAddress2 = $value;
    }

    public function getBillAddress2()
    {
        return $this->billAddress2;
    }

    public function setBillCity(string $value)
    {
        $this->billCity = $value;
    }

    public function getBillCity()
    {
        return $this->billCity;
    }

    public function setBillState(string $value)
    {
        $this->billState = $value;
    }

    public function getBillState()
    {
        return $this->billState;
    }

    public function setBillZip(string $value)
    {
        $this->billZip = $value;
    }

    public function getBillZip()
    {
        return $this->billZip;
    }

    public function setBillCountry(string $value)
    {
        $this->billCountry = $value;
    }

    public function getBillCountry()
    {
        return $this->billCountry;
    }

    public function setPhoneNumber(string $value)
    {
        $this->phoneNumber = $value;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function setEmail(string $value)
    {
        $this->email = $value;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setContactId(int $contactId)
    {
        $this->contactId = $contactId;
    }

    public function getContactId()
    {
        return $this->contactId;
    }

    public function get()
    {
        return $this->data;
    }

    public function set(array $data)
    {
        $this->data = $data;
    }
}
