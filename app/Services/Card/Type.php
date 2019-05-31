<?php

namespace App\Services\Card;

Class Type
{      
    public static $cardTypes = [4 => 'Visa', 5 => 'MasterCard', 3 => 'American Express', 6 => 'Discover'];

    public function getCardType(string $cardNumber)
    {   
        $id = substr($cardNumber, 0, 1);
        
        $type = isset(static::$cardTypes[$id]) ? static::$cardTypes[$id] : '';
        
        if (empty($type)) {
            throw new \Exception(__('card.invalid_card_type'), 1);
        }

        return $type;
    }
}
