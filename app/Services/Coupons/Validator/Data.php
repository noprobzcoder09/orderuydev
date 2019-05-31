<?php

namespace App\Services\Coupons\Validator;

use  App\Services\Coupons\Model\Data as Model;

Class Data
{    
    public function __construct(string $code)
    {
        $this->data = new Model($code);
    }

    public function isExpired(): bool
    {
        $date = $this->data->getExpiredDate();        
        $date1 = new \DateTime($date);
        $date2 = new \DateTime(date('Y-m-d'));

        return $date1 < $date2;
    }
}
