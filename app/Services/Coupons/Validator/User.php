<?php

namespace App\Services\Coupons\Validator;

use App\Services\Coupons\Validator\AbstractValidator;
use App\Services\Coupons\Model\Data;

Class User extends AbstractValidator
{    
    private $userId;

    public function __construct(string $code, int $userId)
    {
        $this->data = new Data($code);
        $this->userId = $userId;
        $this->message = __('coupon.users');

        $this->load();
    }

    public function load()
    {   
        if ($this->userId == 0) {
            return $this->valid = true;
        }
        if (count($this->data->getUsers()) == 0) {
            return $this->valid = true;
        }

        if (!in_array($this->userId, $this->data->getUsers())) {
            return $this->valid = false;
        }

        $this->valid = true;
    }
}
